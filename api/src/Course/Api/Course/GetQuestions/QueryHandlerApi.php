<?php

declare(strict_types=1);

namespace App\Course\Api\Course\GetQuestions;

use App\Course\Query\Course\GetQuestions\Query;
use App\Course\Query\Course\GetQuestions\QueryHandler;

final class QueryHandlerApi
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly QueryHandler $queryHandler,
    ) {}

    public function getQuestions(array $questionIds): array
    {
        return $this->queryHandler->handle(new Query($questionIds));
    }
}
