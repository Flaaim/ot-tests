<?php

declare(strict_types=1);

namespace App\OAuth\Grant;

use App\Infrastructure\Social\Registry\ClientRegistryInterface;
use App\OAuth\Interfaces\SocialAuthInterface;
use DateInterval;
use DomainException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @psalm-suppress PropertyNotSetInConstructor Parent properties are set by OAuth server via setters.
 */
final class SocialGrant extends AbstractGrant
{
    public function __construct(
        private readonly ClientRegistryInterface $registry,
        private readonly SocialAuthInterface $socialAuth,
        private readonly LoggerInterface $logger,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
    ) {
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    public function getIdentifier(): string
    {
        return 'social';
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        $client = $this->validateClient($request);

        $parsedBody = $request->getParsedBody();
        if (!\is_array($parsedBody)) {
            throw OAuthServerException::invalidRequest('network or code');
        }

        $network = $parsedBody['network'] ?? null;
        $code = $parsedBody['code'] ?? null;
        $redirectUri = $parsedBody['redirect_uri'] ?? null;

        if (!\is_string($network) || '' === $network
            || !\is_string($code) || '' === $code
            || !\is_string($redirectUri) || '' === $redirectUri) {
            throw OAuthServerException::invalidRequest('network or code');
        }

        try {
            $socialUser = $this->registry->create($code, $network, $redirectUri);
        } catch (Throwable $e) {
            $this->logger->error('Social Auth Error (Provider fetch): {message}', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw OAuthServerException::serverError('Ошибка авторизации через соцсеть: ' . $e->getMessage());
        }

        try {
            $userIdentifier = $this->socialAuth->authenticate(
                $socialUser->network,
                $socialUser->identity,
                $socialUser->email
            );
        } catch (DomainException $e) {
            throw OAuthServerException::invalidGrant($e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Social Auth Error (Database/Register):', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw OAuthServerException::serverError('Ошибка при регистрации через соцсеть: ' . $e->getMessage());
        }

        try {
            $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));

            $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $userIdentifier, $scopes);

            $refreshToken = $this->issueRefreshToken($accessToken);

            $responseType->setAccessToken($accessToken);
            if (null !== $refreshToken) {
                $responseType->setRefreshToken($refreshToken);
            }

            return $responseType;
        } catch (Throwable $e) {
            $this->logger->error('Social Auth Error (Token Generation):', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw OAuthServerException::serverError('Ошибка на этапе генерации токенов: ' . $e->getMessage());
        }
    }
}
