<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\Get;

use App\Course\Api\Course\GetQuestions\QueryHandlerApi;
use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher,
        private readonly QueryHandlerApi $courseApi,
    ) {}

    public function handle(Query $query): AttemptDTO
    {
        $attempt = $this->fetcher->getOneById($query->id);

        if (empty($attempt)) {
            throw new DomainException('No attempt found.');
        }

        $questions = $this->courseApi->getQuestions($attempt['question_ids']);

        if (empty($questions)) {
            throw new DomainException('No questions found in course.');
        }

        return new AttemptDTO(
            id: $attempt['id'],
            status: $attempt['status'],
            ticketNumber: $attempt['ticket_number'],
            questions: $questions,
        );
    }
}
