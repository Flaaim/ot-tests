<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt;

interface AttemptFetcherInterface
{
    public function getQuestions(string $attemptId): array;
}
