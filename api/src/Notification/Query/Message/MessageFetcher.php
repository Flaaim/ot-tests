<?php

declare(strict_types=1);

namespace App\Notification\Query\Message;

use App\Notification\Entity\Message\Status;
use Doctrine\DBAL\Connection;

final readonly class MessageFetcher implements MessageFetcherInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getUnreadCount(string $profileId): int
    {
        $qb = $this->connection->createQueryBuilder();

        return (int)$qb->select('COUNT(m.message_id)')
            ->from('notification_messages', 'm')
            ->where('m.profile_id = :profileId')
            ->andWhere('m.status = :status')
            ->setParameter('profileId', $profileId)
            ->setParameter('status', Status::NOT_READ->value)
            ->executeQuery()
            ->fetchOne();
    }

    public function get(string $profileId, int $page, int $limit): array
    {
        $page = max(1, $page);
        $limit = min(max(1, $limit), 100);
        $offset = ($page - 1) * $limit;

        $qb = $this->connection->createQueryBuilder();

        $qb->from('notification_messages', 'm');

        $countQb = clone $qb;
        $totalCount = (int)$countQb->select('COUNT(m.message_id)')
            ->leftJoin('m', 'notifications', 'ntf', 'ntf.notification_id = m.notification_id')
            ->where('m.profile_id = :profileId')
            ->setParameter('profileId', $profileId)
            ->executeQuery()
            ->fetchOne();

        $rows = $qb->select(
            'm.message_id,
            ntf.subject,
            ntf.message,
            ntf.created_at,
            m.status'
        )
            ->leftJoin('m', 'notifications', 'ntf', 'ntf.notification_id = m.notification_id')
            ->where('m.profile_id = :profileId')
            ->setParameter('profileId', $profileId)
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
