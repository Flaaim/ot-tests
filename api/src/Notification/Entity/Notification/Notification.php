<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

use App\Notification\Event\NotificationCreated;
use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;

final class Notification implements AggregateRoot
{
    use EventTrait;
    private Status $status;

    public function __construct(
        private NotificationId $notificationId,
        private string $subject,
        private string $message,
        private DateTimeImmutable $createdAt,
    ) {
        $this->status = Status::inProgress();

        $this->recordEvent(new NotificationCreated(
            $this->notificationId->getValue(),
        ));
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
