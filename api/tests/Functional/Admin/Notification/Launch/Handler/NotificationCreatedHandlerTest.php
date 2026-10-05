<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\Launch\Handler;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageRepository;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Notification\Event\NotificationCreated;
use App\Notification\MessageHandler\NotificationCreatedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Functional\FixturesLoader;

final class NotificationCreatedHandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;

    private readonly NotificationRespository $notifications;
    public function setUp(): void
    {
        self::bootKernel();
        $this->container = $this->getContainer();

        $fixtureLoader = new FixturesLoader($this->container);
        $fixtureLoader->loadFixtures([RequestFixture::class]);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $this->notifications = new NotificationRespository($em);
    }

    public function testSuccess(): void
    {
        $message = new NotificationCreated(RequestFixture::NOTIFICATION_ID);
        $handler = $this->container->get(NotificationCreatedHandler::class);

        $handler($message);

        $notification = $this->notifications->get(new NotificationId(RequestFixture::NOTIFICATION_ID));

        self::assertTrue($notification->isCompleted());

        $em = $this->container->get(EntityManagerInterface::class);

        $messageCount = $em->getRepository(Message::class)->count([
            'notificationId' => RequestFixture::NOTIFICATION_ID
        ]);

        self::assertEquals(1, $messageCount);
    }
}
