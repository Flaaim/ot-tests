<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Notification\MessageHandler;

use App\Notification\Entity\Notification\Notification;
use App\Notification\Entity\Notification\NotificationId;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public const string PROFILE_ID = '5b396d0d-a14c-4612-add6-54875f87e41c';
    public const string NOTIFICATION_ID = '2604176a-bf92-45b2-a263-bb5adf07b1b5';

    public function load(ObjectManager $manager): void
    {
        $profile = new ProfileBuilder()
            ->withProfileId(new ProfileId(self::PROFILE_ID))
            ->build();
        $manager->persist($profile);

        $systemNotification = Notification::createSystem(
            new NotificationId(self::NOTIFICATION_ID),
            self::PROFILE_ID,
            'subject',
            'message',
            new DateTimeImmutable(),
        );
        $manager->persist($systemNotification);

        $manager->flush();
    }
}
