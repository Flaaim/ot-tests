<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Profile\Remove;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageId;
use App\Profile\Entity\Profile\Email as ProfileEmail;
use App\Profile\Entity\Profile\Profile;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Entity\Profile\Role;
use App\Profile\Entity\Profile\Status;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string MESSAGE_ID = '1bcf980a-9d30-4b55-bafa-60dc6ad4ece9';

    public function load(ObjectManager $manager): void
    {
        $userProfile = new Profile(
            new ProfileId(RoleFixture::USER_ID),
            new ProfileEmail(RoleFixture::USER_EMAIL),
            Role::user(),
            Status::ok()
        );
        $manager->persist($userProfile);

        $adminProfile = new Profile(
            new ProfileId(RoleFixture::ADMIN_ID),
            new ProfileEmail(RoleFixture::ADMIN_EMAIL),
            Role::admin(),
            Status::ok()
        );
        $manager->persist($adminProfile);

        $message = new Message(
            new MessageId(self::MESSAGE_ID),
            '6dd45e47-e937-4b9c-8db7-45372f9ee423',
            $userProfile->getId()->getValue()
        );
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
