<?php

declare(strict_types=1);

namespace App\Testing\Query\Category;

use Doctrine\DBAL\Connection;

/** @psalm-suppress UnusedClass */
final class CategoryFetcher implements CategoryFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function getAll(): array
    {
        $qb = $this->connection->createQueryBuilder();
        return $qb->select('id, name, slug, parent_id')
            ->from('test_categories')
            ->orderBy('name', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
