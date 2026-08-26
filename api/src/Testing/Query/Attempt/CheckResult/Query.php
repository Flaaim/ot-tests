<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\CheckResult;

final class Query
{
    public function __construct(
        public readonly string $attemptId,
        public readonly string $userId,
    ) {}
}
