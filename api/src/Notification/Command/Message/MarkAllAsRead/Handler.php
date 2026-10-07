<?php

declare(strict_types=1);

namespace App\Notification\Command\Message\MarkAllAsRead;

use App\Notification\Entity\Message\MessageRepository;

final readonly class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageRepository $messages
    ) {}

    public function handle(Command $command): void
    {
        $this->messages->markAllAsRead($command->profileId);
    }
}
