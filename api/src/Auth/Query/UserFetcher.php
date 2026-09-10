<?php

declare(strict_types=1);

namespace App\Auth\Query;

use Doctrine\DBAL\Connection;

/** @psalm-suppress UnusedClass */
final class UserFetcher implements UserFetcherInterface
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

    public function getUsers(): array
    {
        // TODO: Implement getUsers() method.
    }
}
