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

    public function getOneById(string $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('
            p.id,
            p.name,
            p.description,
            p.slug,
            p.parent_id,
            c.id as child_id,
            c.name as child_name,
            c.slug as child_slug,
            c.description as child_description,
            c.parent_id as child_parent_id')
            ->from('test_categories', 'p')
            ->leftJoin('p', 'test_categories', 'c', 'c.parent_id = p.id')
            ->where($qb->expr()->eq('p.id', ':id'))
            ->setParameter('id', $id)
            ->executeQuery();

        $rows = $result->fetchAllAssociative();

        $data = [];

        foreach ($rows as $row) {
            if (empty($data)) {
                $data = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'slug' => $row['slug'],
                    'parent_id' => $row['parent_id'],
                    'children' => [],
                ];
            }

            if (null !== $row['child_id']) {
                $data['children'][] = [
                    'id' => $row['child_id'],
                    'name' => $row['child_name'],
                    'description' => $row['child_description'],
                    'slug' => $row['child_slug'],
                    'parent_id' => $row['child_parent_id'],
                ];
            }
        }
        return $data;
    }
}
