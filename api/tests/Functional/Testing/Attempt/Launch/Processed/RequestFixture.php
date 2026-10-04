<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\Launch\Processed;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Testing\Entity\Attempt\Answer;
use App\Testing\Entity\Attempt\AnswerId;
use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\Status;
use App\Testing\Entity\Test\Settings;
use App\Testing\Entity\Test\TestId;
use App\Testing\Test\Builder\TestBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\Functional\Admin\Course\Course\Get\RequestFixture as CourseGetRequestFixture;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string TEST_ID = '5c77e4dc-f1a2-4e6d-b1cd-9a7bdfa548ce';
    public const string TEST_NAME = 'Первая помощь';
    public const string TEST_CIPHER = 'ОТ 201.18';
    public const int TICKET_NUMBER = 1;

    public const string ATTEMPT_ID = '7dd352b6-ffdb-4473-a7a4-78e3c4f89e51';

    public const string USER_ID = 'edd717b6-b27f-46cd-afbc-2ce32216e429';
    public const string USER_EMAIL = 'attempt@app.test';

    public const string USER_PASSWORD = 'user';

    public function load(ObjectManager $manager): void
    {
        $user = new UserBuilder()
            ->withId(new Id(self::USER_ID))
            ->withEmail(new Email(self::USER_EMAIL))
            ->withPassword(self::USER_PASSWORD)
            ->active()
            ->build();
        $manager->persist($user);

        $test = new TestBuilder()
            ->withId(new TestId(self::TEST_ID))
            ->withName(self::TEST_NAME)
            ->withCipher(self::TEST_CIPHER)
            ->withDescription(self::TEST_NAME)
            ->withSettings(new Settings(5, 2, 1))
            ->withCourseIds([CourseGetRequestFixture::COURSE_ID])
            ->withQuestionIds(CourseGetRequestFixture::QUESTION_IDS)
            ->active()
            ->build();
        $manager->persist($test);

        $attempt = new Attempt(
            new AttemptId(self::ATTEMPT_ID),
            self::TEST_ID,
            $user->getId()->getValue(),
            Status::inProgress(),
            new DateTimeImmutable(),
            self::TICKET_NUMBER,
            [],
            1,
            0
        );
        $manager->persist($attempt);

        $answer = new Answer(
            new AnswerId('193189f9-0feb-4830-bbe8-040fc1aa8113'),
            '193189f9-0feb-4830-bbe8-040fc1aa8113',
            [],
            true,
        );
        $answer->appendAttempt($attempt);
        $manager->persist($answer);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CourseGetRequestFixture::class,
        ];
    }
}
