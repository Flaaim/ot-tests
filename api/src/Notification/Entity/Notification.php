<?php

declare(strict_types=1);

namespace App\Notification\Entity;

use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;

final class Notification implements AggregateRoot
{
    use EventTrait;

    public function __construct(
        private NotificationId $notificationId,
        private string $subject,
        private array $profileIds,
        private Status $status,
        private DateTimeImmutable $createdAt,
    ) {}

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
}
