<?php

declare(strict_types=1);

namespace App\OAuth\Interfaces;

interface ConfirmMagicLinkInterface
{
    public function confirmMagicLink(string $token, string $email, ?string $guestId = null): string;
}
