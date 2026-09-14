<?php

declare(strict_types=1);

namespace App\Profile\Fixture;

use App\Profile\Entity\Profile\Email;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ProfileFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new ProfileBuilder()
            ->withProfileId(new ProfileId('eaa3e157-5017-4d01-84f7-3275f8e4492e'))
            ->withEmail(new Email('flaaim@list.ru'))
            ->build();
        $manager->persist($profile);

        $manager->flush();
    }
}
