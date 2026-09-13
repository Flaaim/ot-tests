<?php

declare(strict_types=1);

namespace App\Profile\Query\GetUsersPaginated;

use App\Profile\Query\ProfileFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly ProfileFetcherInterface $users
    ) {}

    public function handle(Query $query): array
    {
        $safeLimit = max(1, $query->limit);

        $result = $this->users->getUsers($query->page, $query->limit);

        if (empty($result)) {
            throw new DomainException('No results found.');
        }

        $items = array_map(static fn (array $data) => UserDTO::fromArray($data), $result['items'] ?? []);

        $totalCount = $result['totalCount'];

        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $safeLimit) : 0;

        return [
            'items' => $items,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
        ];
    }
}
