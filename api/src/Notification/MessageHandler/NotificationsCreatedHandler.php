<?php

declare(strict_types=1);

namespace App\Notification\MessageHandler;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Message\MessageRepository;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Notification\Event\NotificationCreated;
use App\Profile\Api\GetProfilesIds\QueryHandlerApi;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class NotificationsCreatedHandler
{
    /** @psalm-suppress UnusedClass */
    public function __construct(
        private QueryHandlerApi $profileApi,
        private MessageRepository $messages,
        private NotificationRespository $notifications,
        private Flusher $flusher,
    ) {}

    public function __invoke(NotificationCreated $event): void
    {
        $profileIds = $this->profileApi->getProfileIds();

        $batchSize = 500;
        $count = 0;

        foreach ($profileIds as $profileId) {
            $message = new Message(
                MessageId::generate(),
                $event->notificationId,
                $profileId
            );

            $this->messages->add($message);
            ++$count;

            if (($count % $batchSize) === 0) {
                $this->flusher->flush();
                $this->messages->clear();
            }
        }

        $notification = $this->notifications->get(new NotificationId($event->notificationId));
        $notification->completed();


        $this->flusher->flush();
        $this->messages->clear();


    }
}
