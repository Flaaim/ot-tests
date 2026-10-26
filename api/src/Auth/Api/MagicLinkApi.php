<?php

declare(strict_types=1);

namespace App\Auth\Api;

use App\Auth\Command\JoinByMagicLink\Confirm\Command;
use App\Auth\Command\JoinByMagicLink\Confirm\Handler;
use App\Auth\Entity\User\User;
use App\OAuth\Interfaces\ConfirmMagicLinkInterface;

final class MagicLinkApi implements ConfirmMagicLinkInterface
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function confirmMagicLink(string $token, string $email, ?string $guestId = null): User
    {
        return $this->handler->handle(new Command($token, $email, $guestId));
    }
}
