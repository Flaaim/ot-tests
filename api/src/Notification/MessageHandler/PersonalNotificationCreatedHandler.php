<?php

declare(strict_types=1);

namespace App\Notification\MessageHandler;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Message\MessageRepository;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Notification\Event\PersonalNotificationCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PersonalNotificationCreatedHandler
{
    public function __construct(
        private MessageRepository $messages,
        private Flusher $flusher,
        private NotificationRespository $notifications,
    ) {}

    public function __invoke(PersonalNotificationCreated $event): void
    {
        $notificationId = $event->notificationId;
        $profileId = $event->profileId;

        $message = new Message(
            MessageId::generate(),
            $notificationId,
            $profileId
        );

        $this->messages->add($message);

        $notification = $this->notifications->get(new NotificationId($notificationId));
        $notification->markAsCompleted();

        $this->flusher->flush();
    }
}
