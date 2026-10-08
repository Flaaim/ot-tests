<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\Remove;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\Functional\Admin\Notification\Launch\RoleFixture;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string NOTIFICATION_ID = 'b8699212-02c8-4685-a7d5-935b5ba1bff9';

    public function load(ObjectManager $manager): void
    {
        $notification = Notification::createBroadcast(
            new NotificationId(self::NOTIFICATION_ID),
            'subject',
            'message',
            new DateTimeImmutable()
        );
        $manager->persist($notification);

        $message = new Message(
            new MessageId('b8699212-02c8-4685-a7d5-935b5ba1bff9'),
            $notification->getNotificationId()->getValue(),
            'b8699212-02c8-4685-a7d5-935b5ba1bff9'
        );
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
