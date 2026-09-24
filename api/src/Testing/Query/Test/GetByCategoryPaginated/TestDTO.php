<?php

declare(strict_types=1);

namespace App\Testing\Query\Test\GetByCategoryPaginated;

use DateTimeImmutable;

final class TestDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cipher,
        public string $description,
        public string $slug,
        public string $createdAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            cipher: $data['cipher'],
            description: $data['description'],
            slug: $data['slug'],
            createdAt: new DateTimeImmutable($data['created_at'])->format('Y-m-d'),
        );
    }
}
