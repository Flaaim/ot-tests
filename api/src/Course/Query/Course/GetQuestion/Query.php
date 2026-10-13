<?php

declare(strict_types=1);

namespace App\Course\Query\Course\GetQuestion;

final readonly class Query
{
    public function __construct(
        public string $questionId
    ) {}
}
