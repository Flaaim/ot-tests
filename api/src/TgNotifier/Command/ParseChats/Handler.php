<?php

declare(strict_types=1);

namespace App\TgNotifier\Command\ParseChats;

use App\TgNotifier\Entity\DTO\ChatDTO;
use App\TgNotifier\Event\ChatsParsed;
use DomainException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Yaml\Yaml;

final readonly class Handler
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {}

    public function handle(Command $command): void
    {
        $chats = Yaml::parse($command->text);

        if (!\is_array($chats)) {
            throw new DomainException('Can not parse YAML.');
        }

        $parsedChats = [];
        foreach ($chats as $chat) {
            $parsedChats[] = new ChatDTO(
                Uuid::uuid4()->toString(),
                $chat['name'] ?? 'Unknown',
                (string)$chat['chat_id'],
                (string)$chat['date']
            );
        }

        $this->messageBus->dispatch(new ChatsParsed($parsedChats));
    }
}
