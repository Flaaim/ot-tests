<?php

declare(strict_types=1);

namespace App\Profile\Query;

use Doctrine\DBAL\Connection;

/** @psalm-suppress UnusedClass */
final class ProfileFetcher implements ProfileFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function getProfile(string $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('u.id, u.email', 'un.network', 'un.identity', 'u.role')
            ->from('users', 'u')
            ->leftJoin('u', 'user_networks', 'un', 'u.id = un.user_id')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();
        if (false === $result) {
            return [];
        }

        return [
            'id' => $result['id'],
            'email' => $result['email'],
            'role' => $result['role'],
            'network' => [
                'name' => $result['network'],
                'identity' => $result['identity'],
            ],
        ];
    }

    public function getUsers(int $page, int $limit): array
    {
        $page = max(1, $page);
        $limit = min(max(1, $limit), 100);
        $offset = ($page - 1) * $limit;

        $qb = $this->connection->createQueryBuilder();

        $rows = $qb->select('u.id, u.email', 'u.status', 'u.role, u.date')
            ->from('users', 'u')
            ->orderBy('u.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();

        $countQb = $this->connection->createQueryBuilder();
        $totalCount = $countQb->select('COUNT(u.id)')
            ->from('users', 'u')
            ->executeQuery()
            ->fetchOne();

        return [
            'items' => $rows,
            'totalCount' => $totalCount,
        ];
    }
}
