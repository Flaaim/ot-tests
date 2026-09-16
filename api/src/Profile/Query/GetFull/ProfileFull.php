<?php

declare(strict_types=1);

namespace App\Profile\Query\GetFull;

use DateTimeImmutable;

final class ProfileFull
{
    public function __construct(
        public string $id,
        public string $email,
        public string $role,
        public string $profileStatus,
        public string $date,
        public array $networks,
        public string $authStatus,
        public ?string $passwordHash = null,
        public ?string $name = null,
        public ?string $surname = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $networks = array_map(static fn (array $item) => NetworkDTO::fromArray($item), $data['networks'] ?? []);

        return new self(
            id: $data['id'],
            email: $data['email'],
            role: $data['role'],
            profileStatus: $data['profile_status'],
            date: new DateTimeImmutable($data['date'])->format('Y-m-d'),
            networks: $networks,
            authStatus: $data['auth_status'],
            passwordHash: $data['password_hash'] ?? null,
            name: $data['name'] ?? null,
            surname: $data['surname'] ?? null,
        );
    }
}
