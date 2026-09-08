<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetByUser;

final class AttemptDTO
{
    public function __construct(
        public string $id,
        public string $status,
        public int $score,
        public int $mistakes,
        public string $startedAt,
        public int $ticketNumber,
        public string $name,
        public string $cipher,
        public int $allowedMistakes,
        public ?string $finishedAt = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
            score: $data['score'],
            mistakes: $data['mistakes'],
            startedAt: $data['started_at'],
            ticketNumber: $data['ticket_number'],
            name: $data['name'],
            cipher: $data['cipher'],
            allowedMistakes: $data['allowed_mistakes'],
            finishedAt: $data['finished_at'] ?? null,
        );
    }
}
