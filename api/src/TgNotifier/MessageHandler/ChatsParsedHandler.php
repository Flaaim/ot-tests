<?php

declare(strict_types=1);

namespace App\TgNotifier\MessageHandler;

use App\TgNotifier\Entity\DTO\ChatDTO;
use App\TgNotifier\Event\ChatsParsed;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ChatsParsedHandler
{
    public function __construct(
        private Connection $connection
    ) {}

    public function __invoke(ChatsParsed $event): void
    {
        $chats = $event->chats;
        $chunks = array_chunk($chats, 500);

        foreach ($chunks as $chunk) {
            $values = [];
            $params = [];

            /** @var ChatDTO $chat */
            foreach ($chunk as $chat) {
                $values[] = '(?, ?, ?, ?)';

                $params[] = $chat->id;
                $params[] = $chat->chatId;
                $params[] = $chat->name;
                $params[] = $chat->date;
            }

            $sql = 'INSERT INTO telegram_chats (contact_id, chat_id, name, created_at) VALUES '
                . implode(', ', $values)
                . ' ON CONFLICT (chat_id) DO NOTHING';

            $this->connection->executeStatement($sql, $params);
        }
    }
}
