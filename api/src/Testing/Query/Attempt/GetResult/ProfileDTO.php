<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

final readonly class ProfileDTO
{
    public function __construct(
        public string $email,
        public ?string $name = null,
        public ?string $surname = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            name: $data['name'] ?? null,
            surname: $data['surname'] ?? null,
        );
    }
}
