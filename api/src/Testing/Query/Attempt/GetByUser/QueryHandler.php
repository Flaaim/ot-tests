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

    public function handle(Query $query): array
    {
        $rows = $this->fetcher->getByUser($query->userId);

        if (empty($rows)) {
            throw new DomainException('No results found.');
        }
        return array_map(static fn (array $row) => AttemptDTO::fromArray($row), $rows);
    }
}
