<?php

declare(strict_types=1);

namespace App\Notification\Command\Notification\SendPersonal;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class Handler
{
    public function __construct(
        private NotificationRespository $notifications,
        private Flusher $flusher,
    ) {}

    public function __invoke(Command $command): void
    {
        $notification = Notification::createSystem(
            NotificationId::generate(),
            $command->profileId,
            $command->subject,
            $command->message,
            new DateTimeImmutable(),
        );

        $this->notifications->add($notification);

        $this->flusher->flush();
    }
}
