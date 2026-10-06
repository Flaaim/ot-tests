<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\Functional\Notification\MarkAsRead\ProfileFixture;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string MESSAGE_ID = 'b8699212-02c8-4685-a7d5-935b5ba1bff9';

    public function load(ObjectManager $manager): void
    {
        $message = new Message(
            new MessageId(self::MESSAGE_ID),
            '0a930fe1-ff54-408c-a00c-13b9e623e11f',
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
