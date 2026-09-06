<?php

declare(strict_types=1);

namespace App\Testing\Query\Category\GetOne;

use App\Testing\Query\Category\CategoryFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly CategoryFetcherInterface $categories
    ) {}

    public function handle(Query $query): CategoryDTO
    {
        $row = $this->categories->getOneById($query->id);

        if (empty($row)) {
            throw new DomainException('Category not found.');
        }

        return CategoryDTO::fromArray($row);
    }
}
