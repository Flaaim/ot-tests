<?php

declare(strict_types=1);

namespace App\Notification\Entity;

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
        private array $profileIds,
        private DateTimeImmutable $createdAt,
    ) {
        $this->status = Status::inProgress();
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }

    public function getProfileIds(): array
    {
        return $this->profileIds;
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
