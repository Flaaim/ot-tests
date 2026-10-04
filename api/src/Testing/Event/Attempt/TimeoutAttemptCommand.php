<?php

declare(strict_types=1);

namespace App\Testing\Event\Attempt;

use DateTimeImmutable;

final readonly class TimeoutAttemptCommand
{
    public function __construct(
        public string $attemptId,
        public DateTimeImmutable $startedAt,
    ) {}
}
