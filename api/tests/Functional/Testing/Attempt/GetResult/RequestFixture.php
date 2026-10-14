<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\GetResult;

use App\Auth\Entity\User\Email as UserEmail;
use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Profile\Entity\Profile\Email as ProfileEmail;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Test\Builder\ProfileBuilder;
use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\Status;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const string ATTEMPT_ID = '86732793-3874-4146-bc65-72d3fa75ddcb';
    public const string ATTEMPT_ID_NOT_FOUND = '581cae87-f0b5-4994-a6aa-5821fa2b963a';

    public const string USER_ID = '3772675d-e205-4dd8-84a9-52861773f740';
    public const string USER_EMAIL = 'user@mail.ru';
    public const string USER_PASSWORD = 'user';

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

        $attempt = new Attempt(
            new AttemptId(self::ATTEMPT_ID),
            TestFixture::TEST_ID,
            $user->getId()->getValue(),
            Status::passed(),
            new DateTimeImmutable(),
            TestFixture::TICKET_NUMBER,
            $this->getQuestionsSnapshot()
        );
        $manager->persist($attempt);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TestFixture::class,
        ];
    }

    private function getQuestionsSnapshot(): array
    {
        return [
            [
                'id' => '23ca06c6-6d2d-45d6-ae89-0526afdff6f4',
                'text' => 'Что необходимо сделать после восстановления самостоятельного дыхания у пострадавшего с отсутствующим сознанием?',
                'question_img' => '',
                'answers' => [
                    [
                        'id' => '5a81b5f1089cee2b44809bfda245da59',
                        'text' => 'Продолжить выполнять сердечно-легочную реанимацию до появления сознания у пострадавшего',
                        'isCorrect' => false,
                        'answerImg' => '',
                    ],
                    [
                        'id' => 'a320df35029816f426dde35848e588bb',
                        'text' => 'Дать пострадавшему понюхать нашатырный спирт',
                        'isCorrect' => true,
                        'answerImg' => '',
                    ],
                ],
                'form' => 'single_choice',
            ],
            [
                'id' => '16b1df62-5ad0-4707-ab1c-746affd6670f',
                'text' => 'На какое время допускается снять кровоостанавливающий жгут, если максимальное время его наложения истекло, а пострадавшего не транспортировали в медицинскую организацию?',
                'question_img' => '',
                'answers' => [
                    [
                        'id' => '310eb8b5ef4dc79b46e3f968819d0896',
                        'text' => 'На 15 минут',
                        'isCorrect' => false,
                        'answerImg' => '',
                    ],
                    [
                        'id' => '66bc39ee7187f574dfb8699f74e55863',
                        'text' => 'Снимать жгут не рекомендуется',
                        'isCorrect' => true,
                        'answerImg' => '',
                    ],
                ],
                'form' => 'single_choice',
            ],
        ];
    }
}
