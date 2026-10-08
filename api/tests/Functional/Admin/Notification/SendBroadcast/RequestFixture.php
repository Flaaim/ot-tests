<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\SendBroadcast;

use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string PROFILE_ID = '5b396d0d-a14c-4612-add6-54875f87e41c';

    public function load(ObjectManager $manager): void
    {
        $profile = new ProfileBuilder()
            ->withProfileId(new ProfileId(self::PROFILE_ID))
            ->build();
        $manager->persist($profile);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
