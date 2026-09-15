<?php

declare(strict_types=1);

namespace App\Auth\Service;

final class PasswordGenerator
{
    public function generate(): string
    {
        $length = random_int(6, 15);
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charsLength = \strlen($chars);
        $password = '';

        for ($i = 0; $i < $length; ++$i) {
            $password .= $chars[random_int(0, $charsLength - 1)];
        }
        return $password;
    }
}
