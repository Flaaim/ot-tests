<?php

declare(strict_types=1);

namespace App\Auth\Query\GetUsersPaginated;

/** @psalm-suppress UnusedClass */
final class ListUserDTO
{
    public function __construct(
        public array $items,
        public int $totalCount,
        public int $totalPages,
    ) {}
}
