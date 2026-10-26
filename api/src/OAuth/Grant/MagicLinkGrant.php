<?php

declare(strict_types=1);

namespace App\OAuth\Grant;

use App\OAuth\Interfaces\ConfirmMagicLinkInterface;
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
final class MagicLinkGrant extends AbstractGrant
{
    public function __construct(
        private readonly ConfirmMagicLinkInterface $handler,
        private readonly LoggerInterface $logger,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
    ) {
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    public function getIdentifier(): string
    {
        return 'magic';
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        $client = $this->validateClient($request);

        $parsedBody = $request->getParsedBody();
        if (!\is_array($parsedBody)) {
            throw OAuthServerException::invalidRequest('Invalid parsed body.');
        }

        $email = $parsedBody['email'] ?? null;
        $token = $parsedBody['token'] ?? null;
        $guestId = $parsedBody['guestId'] ?? null;

        if (!\is_string($token) || '' === $token) {
            throw OAuthServerException::invalidRequest('token');
        }

        if (!\is_string($email) || '' === $email) {
            throw OAuthServerException::invalidRequest('email');
        }

        if (null !== $guestId && (!\is_string($guestId) || '' === $guestId)) {
            throw OAuthServerException::invalidRequest('guestId');
        }

        try {
            $userIdentifier = $this->handler->confirmMagicLink($token, $email, $guestId);
        } catch (DomainException $e) {
            throw OAuthServerException::invalidGrant($e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error('Magic link Auth Error (Database/Register):', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw OAuthServerException::serverError('Ошибка проверки магической ссылки: ' . $e->getMessage());
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
            $this->logger->error('Token Generation Error:', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw OAuthServerException::serverError('Ошибка на этапе генерации токенов: ' . $e->getMessage());
        }
    }
}
