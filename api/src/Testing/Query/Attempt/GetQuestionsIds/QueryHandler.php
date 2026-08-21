<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetQuestionsIds;

use App\Course\Api\Course\GetQuestions\QueryHandlerApi;
use App\Testing\Query\Attempt\AttemptFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly AttemptFetcherInterface $fetcher,
        private readonly QueryHandlerApi $courseApi,
    ) {}

    public function handler(Query $query): array
    {
        $questionIds = $this->fetcher->getQuestionIds($query->id);

        if (empty($questionIds)) {
            throw new DomainException('No questions ids found in attempt.');
        }

        $questions = $this->courseApi->getQuestions($questionIds);
        if (empty($questions)) {
            throw new DomainException('No questions found in attempt.');
        }
        return $questions;
    }
}
