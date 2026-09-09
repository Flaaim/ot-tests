<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\Launch\Handler;

use App\Auth\Entity\User\Email;
use App\Auth\Test\Builder\UserBuilder;
use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\Status;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public const string TEST_ID = '5c77e4dc-f1a2-4e6d-b1cd-9a7bdfa548ce';
    public const string USER_EMAIL = 'user@mail.ru';
    public const string USER_PASSWORD = 'user';
    public const int TICKET_NUMBER = 1;
    public const string ATTEMPT_ID = '86732793-3874-4146-bc65-72d3fa75ddcb';

    public function load(ObjectManager $manager): void
    {
        $user = new UserBuilder()
            ->withEmail(new Email(self::USER_EMAIL))
            ->withPassword(self::USER_PASSWORD)
            ->active()
            ->build();
        $manager->persist($user);

        $attempt = new Attempt(
            new AttemptId(self::ATTEMPT_ID),
            self::TEST_ID,
            $user->getId()->getValue(),
            Status::inProgress(),
            new DateTimeImmutable(),
            self::TICKET_NUMBER,
            []
        );
        $manager->persist($attempt);

        $manager->flush();
    }
}
