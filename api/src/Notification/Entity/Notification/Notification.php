<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

use App\Notification\Event\BroadcastNotificationCreated;
use App\Notification\Event\PersonalNotificationCreated;
use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'notifications')]
final class Notification implements AggregateRoot
{
    use EventTrait;
    #[ORM\Column(type: 'notification_status')]
    private Status $status;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'notification_id', unique: true)]
        private NotificationId $notificationId,
        #[ORM\Column(type: 'string', length: 100)]
        private string $subject,
        #[ORM\Column(type: 'text')]
        private string $message,
        #[ORM\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $createdAt,
        #[ORM\Column(type: Types::ENUM)]
        private Type $type
    ) {
        $this->status = Status::inProgress();
    }

    public static function createSystem(
        NotificationId $notificationId,
        string $profileId,
        string $subject,
        string $message,
        DateTimeImmutable $createdAt,
    ): self {
        $notification = new self(
            $notificationId,
            $subject,
            $message,
            $createdAt,
            Type::SYSTEM
        );

        $notification->recordEvent(new PersonalNotificationCreated(
            $notification->getNotificationId()->getValue(),
            $profileId,
        ));

        return $notification;
    }

    public static function createBroadcast(
        NotificationId $notificationId,
        string $subject,
        string $message,
        DateTimeImmutable $createdAt,
    ): self {
        $notification = new self(
            $notificationId,
            $subject,
            $message,
            $createdAt,
            Type::ADMIN
        );

        $notification->recordEvent(new BroadcastNotificationCreated(
            $notification->notificationId->getValue(),
        ));

        return $notification;
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

    public function getType(): Type
    {
        return $this->type;
    }

    public function markAsCompleted(): void
    {
        if ($this->isCompleted()) {
            return;
        }
        $this->status = Status::completed();
    }

    public function isCompleted(): bool
    {
        return $this->status->isCompleted();
    }
}
