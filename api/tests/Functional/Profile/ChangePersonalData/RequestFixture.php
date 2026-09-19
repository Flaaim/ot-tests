<?php

declare(strict_types=1);

namespace Tests\Functional\Profile\ChangePersonalData;

use App\Auth\Entity\User\Email as UserEmail;
use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Profile\Entity\Profile\Email as ProfileEmail;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public const string USER_ID = '887b2e21-842e-44e9-9079-6cfefddd6863';
    public const string USER_EMAIL = 'test@email.ru';
    public const string USER_PASSWORD = 'password';

    public const int NAME_LENGTH = 60;
    public const int SURNAME_LENGTH = 80;

    public function load(ObjectManager $manager): void
    {
        $user = new UserBuilder()
            ->withId(new Id(self::USER_ID))
            ->withEmail(new UserEmail(self::USER_EMAIL))
            ->withPassword(self::USER_PASSWORD)
            ->active()
            ->build();
        $manager->persist($user);

        $profile = new ProfileBuilder()
            ->withProfileId(new ProfileId(self::USER_ID))
            ->withEmail(new ProfileEmail(self::USER_EMAIL))
            ->build();
        $manager->persist($profile);

        $manager->flush();
    }
}
