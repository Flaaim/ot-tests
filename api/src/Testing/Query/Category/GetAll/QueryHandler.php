<?php

declare(strict_types=1);

namespace App\Testing\Query\Category\GetAll;

use App\Testing\Query\Category\CategoryFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly CategoryFetcherInterface $categories
    ) {}

    public function handle(): array
    {
        $rows = $this->categories->getAll();

        if (empty($rows)) {
            throw new DomainException('No categories found');
        }

        $categories = array_map(
            static fn (array $categoryData) => CategoryDTO::fromArray($categoryData),
            $rows
        );

        return $this->buildTree($categories);
    }

    private function buildTree(array $elements, ?string $parentId = null): array
    {
        $branch = [];
        /** @var CategoryDTO $element */
        foreach ($elements as $element) {
            if ($element->parentId === $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
