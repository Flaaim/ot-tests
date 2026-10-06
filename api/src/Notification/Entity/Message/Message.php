<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

#[ORM\Entity]
#[ORM\Table(name: 'notification_messages')]
final class Message
{
    #[ORM\Column(type: Types::ENUM)]
    private Status $status;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'message_id', unique: true)]
        private MessageId $messageId,
        #[ORM\Column(type: 'string')]
        private string $notificationId,
        #[ORM\Column(type: 'string')]
        private string $profileId
    ) {
        $this->status = Status::NOT_READ;
    }

    public function getMessageId(): MessageId
    {
        return $this->messageId;
    }

    public function getNotificationId(): string
    {
        return $this->notificationId;
    }

    public function getProfileId(): string
    {
        return $this->profileId;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function markAsRead(): void
    {
        if ($this->isRead()) {
            throw new DomainException('Message is already read.');
        }
        $this->status = Status::READ;
    }

    public function isRead(): bool
    {
        return Status::READ === $this->status;
    }
}
