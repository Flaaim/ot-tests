<?php

declare(strict_types=1);

namespace App\Testing\Query\Category;

interface CategoryFetcherInterface
{
    public function getAll(): array;

    public function getOneById(string $id): array;
}
