<?php

declare(strict_types=1);

namespace App\TgNotifier\Console;

use App\TgNotifier\Entity\DTO\ChatDTO;
use App\TgNotifier\Event\ChatsParsed;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'app:import-telegram-chats',
    description: 'Парсит chat.yml и добавляет контакты в БД'
)]
final class ParseTelegramChatsCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . '/../../public/Telegram/chat.yml';

        if (!file_exists($filePath)) {
            $io->error('Файл chat.yml не найден!');
            return Command::FAILURE;
        }

        $chats = Yaml::parseFile($filePath);

        if (!\is_array($chats)) {
            $io->error('Файл имеет неверный формат или пуст.');
            return Command::FAILURE;
        }

        $io->info(\sprintf('Найдено %d записей. Начинаем импорт...', \count($chats)));

        $parsedChats = [];
        foreach ($chats as $chat) {
            $parsedChats[] = new ChatDTO(
                Uuid::uuid4()->toString(),
                $chat['name'] ?? 'Unknown',
                (string) $chat['chat_id'],
                (string)$chat['date']
            );
        }

        $this->messageBus->dispatch(new ChatsParsed($parsedChats));

        $io->newLine(2);
        $io->success('Все сообщения успешно отправлены в шину!');

        return Command::SUCCESS;
    }
}
