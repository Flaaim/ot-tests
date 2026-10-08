<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Notification\SendPersonal;

use App\Notification\Command\Notification\SendPersonal\Command;
use App\Notification\Command\Notification\SendPersonal\Handler;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Notification\Event\PersonalNotificationCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

/**
 * @internal
 * @coversNothing
 */
final class HandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;
    private readonly NotificationRespository $notifications;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = $this->getContainer();

        $em = $this->container->get(EntityManagerInterface::class);
        $this->notifications = new NotificationRespository($em);
    }

    public function testSuccess(): void
    {
        /** @var InMemoryTransport $transport */
        $transport = $this->getContainer()->get('messenger.transport.async');
        $transport->reset();

        $command = new Command('10be92d9-48a1-4594-93c4-b703b39f15ab', 'subject', 'message');
        $handler = $this->container->get(Handler::class);

        $handler($command);

        self::assertCount(1, $transport->getSent());

        $message = $transport->getSent()[0]->getMessage();
        self::assertInstanceOf(PersonalNotificationCreated::class, $message);

        self::assertNotNull($message->notificationId);
        self::assertNotNull($message->profileId);

        $notification = $this->notifications->get(new NotificationId($message->notificationId));

        self::assertEquals('subject', $notification->getSubject());
        self::assertEquals('message', $notification->getMessage());
    }
}
