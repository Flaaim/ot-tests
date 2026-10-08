<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message\Get;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string MESSAGE_ID = 'b8699212-02c8-4685-a7d5-935b5ba1bff9';
    public const string NOTIFICATION_ID = 'faa75f4c-8427-4f58-a673-466c1ed5a0ee';

    public function load(ObjectManager $manager): void
    {
        $notification = Notification::createBroadcast(
            new NotificationId(self::NOTIFICATION_ID),
            'Subject',
            'Message',
            new DateTimeImmutable(),
        );
        $manager->persist($notification);

        $message = new Message(
            new MessageId(self::MESSAGE_ID),
            $notification->getNotificationId()->getValue(),
            ProfileFixture::JOHN_ID
        );
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProfileFixture::class,
        ];
    }
}
