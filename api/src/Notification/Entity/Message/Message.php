<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

final class Message
{
    private Status $status;

    public function __construct(
        private MessageId $messageId,
        private string $notificationId,
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
}
