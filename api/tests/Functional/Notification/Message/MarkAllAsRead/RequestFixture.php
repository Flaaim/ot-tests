<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message\MarkAllAsRead;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\Functional\Notification\Message\MarkAsRead\ProfileFixture;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $johnMessage1 = new Message(
            new MessageId('d742648a-dabf-4deb-87ac-cc7543c833dd'),
            '0a930fe1-ff54-408c-a00c-13b9e623e11f',
            ProfileFixture::JOHN_ID
        );
        $manager->persist($johnMessage1);

        $johnMessage2 = new Message(
            new MessageId('9cb1429f-d2ab-4c02-b4e9-e831c587ce52'),
            '64fe1601-631a-40f2-b123-6bebdb988869',
            ProfileFixture::JOHN_ID
        );
        $manager->persist($johnMessage2);

        $aliceMessage1 = new Message(
            new MessageId('29e65e75-bc2f-45d4-993e-7bdc4f3c88ea'),
            '64fe1601-631a-40f2-b123-6bebdb988869',
            ProfileFixture::ALICE_ID
        );
        $manager->persist($aliceMessage1);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProfileFixture::class,
        ];
    }
}
