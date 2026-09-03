<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

final class UserResultDTO
{
    public function __construct(
        public array $selectedIds,
        public bool $isCorrect
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            selectedIds: $data['selected_ids'],
            isCorrect: $data['is_correct'],
        );
    }
}
