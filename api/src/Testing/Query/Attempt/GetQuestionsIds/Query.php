<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetQuestionsIds;

final class Query
{
    public function __construct(
        public readonly string $id,
    ) {}
}
