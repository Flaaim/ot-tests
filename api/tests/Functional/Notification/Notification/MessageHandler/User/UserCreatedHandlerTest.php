<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Notification\MessageHandler\User;

use App\Auth\Event\UserCreated;
use App\Notification\Command\Notification\SendPersonal\Command;
use App\Notification\MessageHandler\User\UserCreatedHandler;
use App\Notification\Service\Templates\WelcomeTemplate;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

/**
 * @internal
 * @coversNothing
 */
final class UserCreatedHandlerTest extends KernelTestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = $this->getContainer();
    }

    public function testSuccess(): void
    {
        /** @var InMemoryTransport $transport */
        $transport = $this->getContainer()->get('messenger.transport.async');
        $transport->reset();

        $message = new UserCreated('27cfc11d-a026-4d98-bde4-c48d45af3f16', 'app@test.ru', 'user');
        $handler = $this->container->get(UserCreatedHandler::class);

        $handler($message);

        self::assertCount(1, $transport->getSent());

        $message = $transport->getSent()[0]->getMessage();

        self::assertInstanceOf(Command::class, $message);

        self::assertEquals(WelcomeTemplate::getSubject(), $message->subject);
        self::assertEquals(WelcomeTemplate::getMessage('app@test.ru'), $message->message);
        self::assertEquals('27cfc11d-a026-4d98-bde4-c48d45af3f16', $message->profileId);
    }
}
