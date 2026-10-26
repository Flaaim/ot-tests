<?php

declare(strict_types=1);

namespace Tests\Functional\OAuth\Magic;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class AuthorizeFixture extends AbstractFixture
{
    public const string USER_TOKEN = 'bb27b246-d0e0-4ea3-ae80-4a9d2a7330a6';
    public const string USER_ID = '3bb0a3ce-59c4-4f7b-a7fa-0e81008ad081';
    public const string USER_EMAIL = 'user@email.ru';
    public const string GUEST_TOKEN = '06650fa5-0e13-4522-b1d0-bb222527484e';
    public const string GUEST_ID = '8f98e983-85c5-4689-b0fe-ce351ef6c9eb';
    public const string GUEST_EMAIL = 'guest@email.ru';

    public const string NOT_FOUND_TOKEN = 'c7c784b1-4af7-4a6e-82ff-9259b449df39';

    public const string EXPIRED_ID = 'caa324e6-e0c8-43a5-8a85-335385f92191';
    public const string EXPIRED_TOKEN = 'a7033bd6-ac61-4398-84fe-8673750e9ac3';
    public const string EXPIRED_EMAIL = 'expired@email.ru';

    public const string ALREADY_ID = '8c82e10c-0997-48ea-aa4d-4c9407a6abee';
    public const string ALREADY_TOKEN = '1b08fe26-d261-4242-94e0-da235da77c2e';
    public const string ALREADY_EMAIL = 'already@email.ru';

    public function load(ObjectManager $manager): void
    {
        $userMagicLinkToken = new Token(self::USER_TOKEN, new DateTimeImmutable('+ 1 hour'));

        $user = new UserBuilder()
            ->withId(new Id(self::USER_ID))
            ->withEmail(new Email(self::USER_EMAIL))
            ->withMagicLinkToken($userMagicLinkToken)
            ->build();
        $manager->persist($user);

        $guestMagicLinkToken = new Token(self::GUEST_TOKEN, new DateTimeImmutable('+ 1 hour'));

        $guest = new UserBuilder()
            ->withId(new Id(self::GUEST_ID))
            ->withEmail(new Email(self::GUEST_EMAIL))
            ->withMagicLinkToken($guestMagicLinkToken)
            ->build();
        $manager->persist($guest);

        $expiredMagicLinkToken = new Token(self::EXPIRED_TOKEN, new DateTimeImmutable('- 1 hour'));

        $expired = new UserBuilder()
            ->withId(new Id(self::EXPIRED_ID))
            ->withEmail(new Email(self::EXPIRED_EMAIL))
            ->withMagicLinkToken($expiredMagicLinkToken)
            ->build();
        $manager->persist($expired);

        $alreadyMagicLinkToken = new Token(self::ALREADY_TOKEN, new DateTimeImmutable('+ 1 hour'));
        $already = new UserBuilder()
            ->withId(new Id(self::ALREADY_ID))
            ->withEmail(new Email(self::ALREADY_EMAIL))
            ->withMagicLinkToken($alreadyMagicLinkToken)
            ->confirmedByMagicLink()
            ->build();
        $manager->persist($already);

        $manager->flush();
    }
}
