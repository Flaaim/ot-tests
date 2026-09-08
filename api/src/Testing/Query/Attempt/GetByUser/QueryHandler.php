<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetByUser;

use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher
    ) {}

    public function handle(Query $query): ListAttemptDTO
    {
        $safeLimit = max(1, $query->limit);

        $result = $this->fetcher->getByUser($query->userId, $query->page, $query->limit);

        if (empty($result)) {
            throw new DomainException('No results found.');
        }

        $items = array_map(static fn (array $row) => AttemptDTO::fromArray($row), $result['items'] ?? []);

        $totalCount = $result['totalCount'];

        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $safeLimit) : 0;

        return new ListAttemptDTO(
            items: $items,
            totalCount: $totalCount,
            totalPages: $totalPages
        );
    }
}
