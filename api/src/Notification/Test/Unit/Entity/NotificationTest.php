<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity;

use App\Notification\Entity\Notification;
use App\Notification\Entity\NotificationId;
use App\Notification\Entity\Status;
use PHPUnit\Framework\TestCase;

final class NotificationTest extends TestCase
{
    public function testNotification(): void
    {
        $test = new Notification(
            $notificationId = NotificationId::generate(),
            $subject = 'test subject',
            $message = 'test message',
            $profileIds = ['75c39a66-b726-4d15-b79b-fd32cfb42fdd', '47658dbe-7740-4a34-9838-8851c569064e'],
            $createdAt = new \DateTimeImmutable(),
        );

        self::assertEquals($notificationId, $test->getNotificationId());
        self::assertEquals($subject, $test->getSubject());
        self::assertEquals($message, $test->getMessage());
        self::assertEquals($profileIds, $test->getProfileIds());
        self::assertEquals($createdAt, $test->getCreatedAt());
        self::assertEquals(Status::IN_PROGRESS, $test->getStatus()->getValue());
    }
}
