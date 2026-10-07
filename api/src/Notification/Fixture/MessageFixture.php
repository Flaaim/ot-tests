<?php

declare(strict_types=1);

namespace App\Notification\Fixture;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class MessageFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $message = new Message(
            new MessageId('72f1a6a2-6509-4b9e-a628-19c8c8760969'),
            NotificationFixture::NOTIFICATION_1_ID,
            'd2fa3777-c5e5-4521-88df-68da6defd00a'
        );
        $manager->persist($message);

        $message = new Message(
            new MessageId('dfaa11f3-29f5-445f-aabf-2ef91f6e8221'),
            NotificationFixture::NOTIFICATION_2_ID,
            'd2fa3777-c5e5-4521-88df-68da6defd00a'
        );
        $message->markAsRead();
        $manager->persist($message);

        $manager->flush();
    }
}
