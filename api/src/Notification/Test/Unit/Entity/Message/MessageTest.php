<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity\Message;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Message\Status;
use App\Notification\Entity\Notification\NotificationId;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    public function testMessage(): void
    {
        $message = new Message(
            $messageId = MessageId::generate(),
            $notificationId = '9b34571a-ad30-4e97-9d29-c65105efac8a',
            $profileId = '45ce2bb3-80d2-4539-b3fc-25b46337b3a5'
        );

        self::assertEquals($messageId, $message->getMessageId()->getValue());
        self::assertEquals($notificationId, $message->getNotificationId());
        self::assertEquals($profileId, $message->getProfileId());
        self::assertEquals(Status::NOT_READ->value, $message->getStatus()->value);
    }
}
