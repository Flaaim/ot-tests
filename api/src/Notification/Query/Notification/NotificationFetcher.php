<?php

declare(strict_types=1);

namespace App\Notification\Query\Notification;

use Doctrine\DBAL\Connection;

final readonly class NotificationFetcher implements NotificationFetcherInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getPaginated(int $page, int $limit = 15): array
    {
        $page = max(1, $page);
        $limit = min(max(1, $limit), 100);
        $offset = ($page - 1) * $limit;

        $qb = $this->connection->createQueryBuilder();

        $qb->from('notifications', 'ntf');

        $countQb = clone $qb;
        $totalCount = (int)$countQb->select('COUNT(ntf.notification_id)')
            ->executeQuery()
            ->fetchOne();

        $rows = $qb->select(
            'ntf.notification_id',
            'ntf.status',
            'ntf.subject',
            'ntf.created_at',
            'COUNT(m.message_id) as count_messages',
            "COUNT(m.message_id) FILTER (WHERE m.status = 'read') as read_count_messages"
        )
            ->leftJoin('ntf', 'notification_messages', 'm', 'ntf.notification_id = m.notification_id')
            ->groupBy(
                'ntf.notification_id',
                'ntf.status',
                'ntf.subject',
                'ntf.created_at'
            )
            ->orderBy('ntf.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();

        return [
            'items' => $rows,
            'totalCount' => $totalCount,
        ];
    }
}
