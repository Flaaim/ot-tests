<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth;

use App\OAuth\Grant\MagicLinkGrant;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: SocialGrantConfiguratorDecorator::class)]
final readonly class MagicLinkGrantConfiguratorDecorator
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private SocialGrantConfiguratorDecorator $inner,
        private MagicLinkGrant $magicLinkGrant,
    ) {}

    public function __invoke(AuthorizationServer $authorizationServer): void
    {
        ($this->inner)($authorizationServer);
        $authorizationServer->enableGrantType($this->magicLinkGrant, new DateInterval('PT1H'));
    }
}
