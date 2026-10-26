<?php

declare(strict_types=1);

namespace App\OAuth\Interfaces;

interface SocialAuthInterface
{
    public function authenticate(string $network, string $identity, string $email): string;
}
