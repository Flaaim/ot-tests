<?php

declare(strict_types=1);

namespace App\Course\Query\Course\GetQuestions;

final class Query
{
    public function __construct(
        public readonly array $questionIds
    ) {}
}
