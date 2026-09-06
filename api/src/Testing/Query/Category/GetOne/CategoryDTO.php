<?php

declare(strict_types=1);

namespace App\Testing\Query\Category\GetOne;

final class CategoryDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public string $description,
        public ?string $parentId = null,
        public array $children = [],
    ) {}

    public static function fromArray(array $categoryData): self
    {
        $children = array_map(static fn (array $child) => self::fromArray($child), $categoryData['children'] ?? []);

        return new self(
            id: $categoryData['id'],
            name: $categoryData['name'],
            slug: $categoryData['slug'],
            description: $categoryData['description'],
            parentId: $categoryData['parent_id'] ?? null,
            children: $children,
        );
    }
}
