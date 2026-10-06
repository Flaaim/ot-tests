<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\GetPaginated;

use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string NOTIFICATION_ID = 'b8699212-02c8-4685-a7d5-935b5ba1bff9';

    public function load(ObjectManager $manager): void
    {
        $notification = new Notification(
            new NotificationId(self::NOTIFICATION_ID),
            'subject',
            'message',
            new DateTimeImmutable(),
        );
        $manager->persist($notification);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
