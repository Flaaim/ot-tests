<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetQuestions;

use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher,
    ) {}

    public function handler(Query $query): array
    {
        $rows = $this->fetcher->getQuestions($query->id);

        if (empty($rows)) {
            throw new DomainException('No questions found.');
        }

        return array_map(
            static fn (array $question) => QuestionDTO::fromArray($question),
            $rows
        );
    }
}
