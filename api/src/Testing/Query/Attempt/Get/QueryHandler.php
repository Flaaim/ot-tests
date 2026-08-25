<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\Get;

use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher
    ) {}

    public function handle(Query $query): AttemptDTO
    {
        $attempt = $this->fetcher->getOneById($query->id);

        if (empty($attempt)) {
            throw new DomainException('No attempt found.');
        }

        return new AttemptDTO(
            id: $attempt['id'],
            status: $attempt['status'],
            ticketNumber: $attempt['ticket_number'],
            questions: $attempt['questions_snapshot'],
        );
    }
}
