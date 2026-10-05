<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Profile\Remove;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Role as UserRole;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RoleFixture extends AbstractFixture
{
    public const string USER_ID = '2a974384-bcf1-4c48-ab4b-59b241420436';
    public const string ADMIN_ID = '238d5e02-0a7b-4cbb-8aea-e8873458d287';

    public const string USER_EMAIL = 'user@mail.ru';
    public const string USER_PASSWORD = 'user';

    public const string ADMIN_EMAIL = 'admin@mail.ru';
    public const string ADMIN_PASSWORD = 'admin';

    public function load(ObjectManager $manager): void
    {
        $user = new UserBuilder()
            ->withId(new Id(self::USER_ID))
            ->withEmail(new Email(self::USER_EMAIL))
            ->withPassword(self::USER_PASSWORD)
            ->active()
            ->build();
        $manager->persist($user);

        $admin = new UserBuilder()
            ->withEmail(new Email(self::ADMIN_EMAIL))
            ->withPassword(self::ADMIN_PASSWORD)
            ->withRole(UserRole::admin())
            ->active()
            ->build();
        $manager->persist($admin);

        $manager->flush();
    }
}
