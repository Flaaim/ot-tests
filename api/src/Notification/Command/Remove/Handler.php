<?php

declare(strict_types=1);

namespace App\Notification\Command\Remove;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Message\MessageRepository;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;

final readonly class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private NotificationRespository $notifications,
        private MessageRepository $messages,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $notification = $this->notifications->get(new NotificationId($command->notificationId));

        $this->messages->remove($notification->getNotificationId()->getValue());

        $this->notifications->remove($notification);

        $this->flusher->flush();
    }
}
