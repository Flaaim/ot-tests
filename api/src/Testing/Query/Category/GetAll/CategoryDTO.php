<?php

declare(strict_types=1);

namespace App\Testing\Query\Category\GetAll;

final class CategoryDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $slug,
        public ?string $parentId = null,
        public array $children = [],
    ) {}

    public static function fromArray(array $categoryData): self
    {
        return new self(
            id: $categoryData['id'],
            name: $categoryData['name'],
            description: $categoryData['description'],
            slug: $categoryData['slug'],
            parentId: $categoryData['parent_id'],
        );
    }
}
