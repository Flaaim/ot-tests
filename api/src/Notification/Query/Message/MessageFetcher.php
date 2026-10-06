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

    public function getLatestMessages(string $profileId, int $limit): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select(
            'm.message_id,
            ntf.subject,
            ntf.message,
            ntf.created_at,
            m.status'
        )
            ->from('notification_messages', 'm')
            ->leftJoin('m', 'notifications', 'ntf', 'ntf.notification_id = m.notification_id')
            ->where('m.profile_id = :profileId')
            ->setParameter('profileId', $profileId)
            ->orderBy('ntf.created_at', 'DESC')
            ->setMaxResults($limit);

        return $result->executeQuery()->fetchAllAssociative();
    }
}
