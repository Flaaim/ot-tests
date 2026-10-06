<?php

declare(strict_types=1);

namespace App\Notification\Command\MarkAsRead;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Message\MessageRepository;
use DomainException;

final readonly class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageRepository $messages,
        private Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $message = $this->messages->get(new MessageId($command->messageId));

        if ($message->getProfileId() !== $command->profileId) {
            throw new DomainException('Вы не можете прочитать чужое уведомление.');
        }

        $message->markAsRead();

        $this->flusher->flush();
    }
}
