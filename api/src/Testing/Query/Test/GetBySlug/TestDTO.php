<?php

declare(strict_types=1);

namespace App\Testing\Query\Test\GetBySlug;

use DateTimeImmutable;

final class TestDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cipher,
        public string $description,
        public array $tickets,
        public string $slug,
        public string $createdAt,
        public string $status,
        public array $settings,
        public array $category
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            cipher: $data['cipher'],
            description: $data['description'],
            tickets: $data['tickets'],
            slug: $data['slug'],
            createdAt: new DateTimeImmutable($data['createdAt'])->format('Y-m-d'),
            status: $data['status'],
            settings: $data['settings'] ?? [],
            category: $data['category'] ?? []
        );
    }
}
