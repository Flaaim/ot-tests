<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message\GetLatest;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ProfileFixture extends AbstractFixture
{
    public const string JOHN_ID = '8757a5bc-6b0d-4766-8e5c-975797730ec0';
    public const string JOHN_EMAIL = 'john@mail.ru';
    public const string ALICE_ID = '95526b64-f063-43d5-8ad8-805c1040f495';
    public const string ALICE_EMAIL = 'alice@mail.ru';
    public const string PASSWORD = 'password';

    public function load(ObjectManager $manager): void
    {
        $john = new UserBuilder()
            ->withId(new Id(self::JOHN_ID))
            ->withEmail(new Email(self::JOHN_EMAIL))
            ->withPassword(self::PASSWORD)
            ->active()
            ->build();
        $manager->persist($john);

        $johnProfile = new ProfileBuilder()
            ->withProfileId(new ProfileId($john->getId()->getValue()))
            ->build();
        $manager->persist($johnProfile);

        $alice = new UserBuilder()
            ->withId(new Id(self::ALICE_ID))
            ->withEmail(new Email(self::ALICE_EMAIL))
            ->withPassword(self::PASSWORD)
            ->active()
            ->build();
        $manager->persist($alice);

        $aliceProfile = new ProfileBuilder()
            ->withProfileId(new ProfileId($alice->getId()->getValue()))
            ->build();
        $manager->persist($aliceProfile);

        $manager->flush();
    }
}
