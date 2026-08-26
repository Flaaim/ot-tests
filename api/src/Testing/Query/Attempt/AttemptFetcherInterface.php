<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt;

interface AttemptFetcherInterface
{
    public function getOneById(string $attemptId): array;

    public function getAttemptResult(string $attemptId): array;
}
