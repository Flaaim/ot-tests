<?php

declare(strict_types=1);

namespace App\Notification\Command\Notification\SendBroadcast;

use App\Infrastructure\Doctrine\Flusher;
use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Profile\Api\HasProfiles\QueryHandlerApi;
use DateTimeImmutable;
use DomainException;

final readonly class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private QueryHandlerApi $queryHandlerApi,
        private NotificationRespository $notifications,
        private Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        if (!$this->queryHandlerApi->hasProfiles()) {
            throw new DomainException('Profiles not found.');
        }

        $notification = Notification::createBroadcast(
            NotificationId::generate(),
            $command->subject,
            $command->message,
            new DateTimeImmutable()
        );

        $this->notifications->add($notification);

        $this->flusher->flush();
    }
}
