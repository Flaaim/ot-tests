<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\Get;

final class AttemptDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $status,
        public readonly int $ticketNumber,
        public readonly array $questions,
    ) {}
}
