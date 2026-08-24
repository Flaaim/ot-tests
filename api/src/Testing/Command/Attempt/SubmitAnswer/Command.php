<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\SubmitAnswer;

final class Command
{
    public function __construct(
        public readonly string $attemptId,
        public readonly string $testId,
        public readonly array $selectedAnswersIds
    ) {}
}
