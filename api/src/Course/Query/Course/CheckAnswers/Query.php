<?php

declare(strict_types=1);

namespace App\Course\Query\Course\CheckAnswers;

final class Query
{
    public function __construct(
        public readonly string $questionId,
        public readonly array $selectedAnswersIds,
    ) {}
}
