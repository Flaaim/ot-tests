<?php

declare(strict_types=1);

namespace Tests\Functional\Auth\JoinByMagicLink;

use App\Auth\Command\JoinByMagicLink\Confirm\Command;
use App\Auth\Command\JoinByMagicLink\Confirm\Handler;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Event\GuestJoined;
use App\Auth\Event\UserJoinConfirmed;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Tests\Functional\FixturesLoader;

/**
 * @internal
 * @coversNothing
 */
final class ConfirmActionTest extends KernelTestCase
{
    private UserRepository $users;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->container = self::getContainer();

        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([ConfirmFixture::class]);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $this->users = new UserRepository($em);
    }

    public function testSuccess(): void
    {
        $handler = $this->container->get(Handler::class);
        $command = new Command(ConfirmFixture::USER_TOKEN, ConfirmFixture::USER_EMAIL);
        $user = $handler->handle($command);

        self::assertEquals(ConfirmFixture::USER_EMAIL, $user->getEmail()->getValue());
    }

    public function testSuccessGuest(): void
    {
        /** @var InMemoryTransport $transport */
        $transport = $this->container->get('messenger.transport.async');
        $transport->reset();

        $handler = $this->container->get(Handler::class);
        $command = new Command(
            ConfirmFixture::GUEST_TOKEN,
            ConfirmFixture::GUEST_EMAIL,
            ConfirmFixture::GUEST_ID
        );

        $handler->handle($command);

        $user = $this->users->get(new Id(ConfirmFixture::GUEST_ID));
        self::assertTrue($user->isActive());
        self::assertNull($user->getMagicLinkToken());

        self::assertCount(2, $transport->getSent());

        $userCreatedMessages = array_filter($transport->getSent(), static fn ($message) => $message->getMessage() instanceof UserJoinConfirmed);
        $userCreatedMessages = array_values($userCreatedMessages);
        $userCreateMessage = $userCreatedMessages[0]->getMessage();

        self::assertNotNull($userCreateMessage->email);

        $guestJoinedMessages = array_filter($transport->getSent(), static fn ($message) => $message->getMessage() instanceof GuestJoined);
        $guestJoinedMessages = array_values($guestJoinedMessages);
        $guestJoinedMessage = $guestJoinedMessages[0]->getMessage();
        self::assertEquals(ConfirmFixture::GUEST_ID, $guestJoinedMessage->guestId);
        self::assertEquals(ConfirmFixture::GUEST_ID, $guestJoinedMessage->userId);
    }

    public function testElseEmail(): void
    {
        $handler = $this->container->get(Handler::class);
        $command = new Command(
            ConfirmFixture::GUEST_TOKEN,
            ConfirmFixture::USER_EMAIL,
            ConfirmFixture::GUEST_ID
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage('Incorrect magicLinkEmail.');

        $handler->handle($command);
    }

    public function testExpired(): void
    {
        $handler = $this->container->get(Handler::class);
        $command = new Command(
            ConfirmFixture::EXPIRED_TOKEN,
            ConfirmFixture::EXPIRED_EMAIL
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage('Token is expired.');

        $handler->handle($command);
    }

    public function testTokenNotFound(): void
    {
        $handler = $this->container->get(Handler::class);
        $command = new Command(
            ConfirmFixture::NOT_FOUND_TOKEN,
            ConfirmFixture::GUEST_EMAIL,
            ConfirmFixture::GUEST_ID
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage('Incorrect magicLinkToken.');

        $handler->handle($command);
    }
}
