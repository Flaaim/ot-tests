<?php

declare(strict_types=1);

namespace App\Notification\Query\Notification\GetPaginated;

final class ListNotificationDTO
{
    public function __construct(
        /** @var NotificationDTO[] $items */
        public array $items,
        public int $totalCount,
        public int $totalPages,
    ) {}
}
