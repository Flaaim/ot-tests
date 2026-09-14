<?php

declare(strict_types=1);

namespace Tests\Functional\Auth\MessageHandler\RemoveProfile;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public const string USER_ID = '2a974384-bcf1-4c48-ab4b-59b241420436';
    public const string USER_EMAIL = 'active@email.com';

    public function load(ObjectManager $manager): void
    {
        $user = new UserBuilder()
            ->withId(new Id(self::USER_ID))
            ->withEmail(new Email(self::USER_EMAIL))
            ->active()
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
