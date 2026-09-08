<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetByUser;

final class ListAttemptDTO
{
    public function __construct(
        /** @var AttemptDTO[] $items */
        public array $items,
        public int $totalCount,
        public int $totalPages,
    ) {}
}
