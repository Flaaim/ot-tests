<?php

declare(strict_types=1);

namespace App\OAuth\Interfaces;

use App\Auth\Entity\User\User;

interface ConfirmMagicLinkInterface
{
    public function confirmMagicLink(string $token, string $email, ?string $guestId = null): User;
}
