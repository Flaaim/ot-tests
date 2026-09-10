<?php

declare(strict_types=1);

namespace App\Auth\Query\GetUsersPaginated;

use DateTimeImmutable;

final class UserDTO
{
    public function __construct(
        public string $id,
        public string $email,
        public string $status,
        public string $role,
        public string $date,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            email: $data['email'],
            status: $data['status'],
            role: $data['role'],
            date: new DateTimeImmutable($data['date'])->format('Y-m-d'),
        );
    }
}
