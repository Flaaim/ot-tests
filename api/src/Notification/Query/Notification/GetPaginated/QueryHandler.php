<?php

declare(strict_types=1);

namespace App\Notification\Query\Notification\GetPaginated;

use App\Notification\Query\Notification\NotificationFetcherInterface;

final readonly class QueryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private NotificationFetcherInterface $notifications,
    ) {}

    public function handle(Query $query): ListNotificationDTO
    {
        $safeLimit = max(1, $query->limit);

        $result = $this->notifications->getPaginated(
            $query->page,
            $query->limit,
        );

        $items = array_map(
            static fn (array $notification): NotificationDTO => NotificationDTO::fromArray($notification),
            $result['items']
        );

        $totalCount = $result['totalCount'];

        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $safeLimit) : 0;

        return new ListNotificationDTO(
            items: $items,
            totalCount: $totalCount,
            totalPages: $totalPages,
        );
    }
}
