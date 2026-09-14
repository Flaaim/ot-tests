<?php

declare(strict_types=1);

namespace App\Profile\Event;

final class ProfileAdded
{
    public function __construct(
        public string $id,
        public string $email,
        public string $password
    ) {}
}
