<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\Get;

final class ListMessageDTO
{
    public function __construct(
        /** @var MessageDTO[] $items */
        public array $items,
        public int $totalCount,
        public int $totalPages,
    ) {}
}
