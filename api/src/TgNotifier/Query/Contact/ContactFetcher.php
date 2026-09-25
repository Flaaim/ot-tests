<?php

declare(strict_types=1);

namespace App\TgNotifier\Query\Contact;

use Doctrine\DBAL\Connection;

final readonly class ContactFetcher implements ContactFetcherInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getAll(): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('tc.id, tc.name, tc.chat_id')
            ->from('telegram_chats', 'tc')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
