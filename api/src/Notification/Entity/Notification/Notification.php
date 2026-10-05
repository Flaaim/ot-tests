<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

use App\Notification\Event\NotificationCreated;
use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'notifications')]
final class Notification implements AggregateRoot
{
    use EventTrait;
    #[ORM\Column(type: 'notification_status')]
    private Status $status;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'notification_id', unique: true)]
        private NotificationId $notificationId,
        #[ORM\Column(type: 'string', length: 100)]
        private string $subject,
        #[ORM\Column(type: 'text')]
        private string $message,
        #[ORM\Column(type: 'datetime_immutable')]
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

    public function completed(): void
    {
        $this->status = Status::completed();
    }
}
