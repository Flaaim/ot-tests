<?php

declare(strict_types=1);

namespace App\Auth\Event;

final class UserCreated
{
    public function __construct(
        public string $id,
        public string $email,
        public string $role,
    ) {}
}
