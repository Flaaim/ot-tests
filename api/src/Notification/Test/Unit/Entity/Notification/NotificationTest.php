<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity\Notification;

use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\Status;
use App\Notification\Entity\Notification\Type;
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
        $test = Notification::createBroadcast(
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
        self::assertEquals(Type::ADMIN->value, $test->getType()->value);
    }

    public function testComplete(): void
    {
        $notification = Notification::createBroadcast(
            NotificationId::generate(),
            'test subject',
            'test message',
            new DateTimeImmutable(),
        );

        $notification->markAsCompleted();
        self::assertEquals(Status::COMPLETED, $notification->getStatus()->getValue());
    }
}
