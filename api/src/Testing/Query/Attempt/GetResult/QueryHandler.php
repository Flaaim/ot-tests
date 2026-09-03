<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher,
    ) {}

    public function handle(Query $query): AttemptResultDTO
    {
        $row = $this->fetcher->getAttemptResult($query->attemptId, $query->userId);

        if (empty($row)) {
            throw new DomainException('Attempt result not found.');
        }

        return AttemptResultDTO::fromArray($row);
    }
}
