<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Profile\Remove\Handler;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public const string PROFILE_ID = '5b396d0d-a14c-4612-add6-54875f87e41c';
    public const string MESSAGE_ID = '6945eedb-2959-4401-8c0c-a088f31b303a';

    public function load(ObjectManager $manager): void
    {
        $profile = new ProfileBuilder()
            ->withProfileId(new ProfileId(self::PROFILE_ID))
            ->build();
        $manager->persist($profile);

        $message = new Message(
            new MessageId(self::MESSAGE_ID),
            '1b5b17e1-c843-4f02-9efa-833e4885a228',
            $profile->getId()->getValue()
        );
        $manager->persist($message);

        $manager->flush();
    }
}
