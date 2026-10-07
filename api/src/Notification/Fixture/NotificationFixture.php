<?php

declare(strict_types=1);

namespace App\Notification\Fixture;

use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class NotificationFixture extends AbstractFixture
{
    public const string NOTIFICATION_1_ID = '119d46e8-fb05-4191-9a8b-85a9933d69ec';
    public const string NOTIFICATION_2_ID = '24954a7b-9be0-460f-aa37-0a64fa4aa147';

    public function load(ObjectManager $manager): void
    {
        $notification1 = new Notification(
            new NotificationId(self::NOTIFICATION_1_ID),
            'Subject1',
            'Message1',
            new DateTimeImmutable(),
        );
        $manager->persist($notification1);

        $notification2 = new Notification(
            new NotificationId(self::NOTIFICATION_2_ID),
            'Subject2',
            'Message2',
            new DateTimeImmutable(),
        );
        $manager->persist($notification2);

        $manager->flush();
    }
}
