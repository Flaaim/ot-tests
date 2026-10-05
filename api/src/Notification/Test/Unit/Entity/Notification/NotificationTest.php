<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity\Notification;

use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\Status;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class NotificationTest extends TestCase
{
    public function testNotification(): void
    {
        $test = new Notification(
            $notificationId = NotificationId::generate(),
            $subject = 'test subject',
            $message = 'test message',
            $createdAt = new DateTimeImmutable(),
        );

        self::assertEquals($notificationId, $test->getNotificationId());
        self::assertEquals($subject, $test->getSubject());
        self::assertEquals($message, $test->getMessage());
        self::assertEquals($createdAt, $test->getCreatedAt());
        self::assertEquals(Status::IN_PROGRESS, $test->getStatus()->getValue());
    }
}
