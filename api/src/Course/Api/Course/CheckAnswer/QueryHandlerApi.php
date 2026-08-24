<?php

declare(strict_types=1);

namespace App\Course\Api\Course\CheckAnswer;

use App\Course\Query\Course\CheckAnswers\Query;
use App\Course\Query\Course\CheckAnswers\QueryHandler;

final class QueryHandlerApi
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly QueryHandler $queryHandler
    ) {}

    public function handle(string $questionId, array $selectedAnswerIds): bool
    {
        return $this->queryHandler->handle(new Query($questionId, $selectedAnswerIds));
    }
}
