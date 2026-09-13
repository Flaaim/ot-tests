<?php

declare(strict_types=1);

namespace App\Profile\Query\Get;

final class ProfileDTO
{
    public function __construct(
        public string $id,
        public string $email,
        public string $role,
        public array $networks = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $networks = array_map(static fn (array $item) => NetworkDTO::fromArray($item), $data['networks'] ?? []);

        return new self(
            id: $data['id'],
            email: $data['email'],
            role: $data['role'],
            networks: $networks
        );
    }
}
