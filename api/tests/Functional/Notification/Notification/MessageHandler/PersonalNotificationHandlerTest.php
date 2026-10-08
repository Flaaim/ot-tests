<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Notification\MessageHandler;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageRepository;
use App\Notification\Entity\Notification\NotificationId;
use App\Notification\Entity\Notification\NotificationRespository;
use App\Notification\Event\PersonalNotificationCreated;
use App\Notification\MessageHandler\PersonalNotificationCreatedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Functional\FixturesLoader;

/**
 * @internal
 * @coversNothing
 */
final class PersonalNotificationHandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;
    private readonly MessageRepository $messages;
    private readonly NotificationRespository $notifications;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = $this->getContainer();

        $em = $this->container->get(EntityManagerInterface::class);
        $this->messages = new MessageRepository($em);
        $this->notifications = new NotificationRespository($em);

        $fixtureLoader = new FixturesLoader($this->container);
        $fixtureLoader->loadFixtures([RequestFixture::class]);
    }

    public function testSuccess(): void
    {
        $message = new PersonalNotificationCreated(
            RequestFixture::NOTIFICATION_ID,
            RequestFixture::PROFILE_ID
        );
        $handler = $this->container->get(PersonalNotificationCreatedHandler::class);

        $handler($message);

        $em = $this->container->get(EntityManagerInterface::class);

        $countMessage = $em->getRepository(Message::class)->count(['notificationId' => RequestFixture::NOTIFICATION_ID]);

        self::assertEquals(1, $countMessage);

        $notification = $this->notifications->get(new NotificationId(RequestFixture::NOTIFICATION_ID));

        self::assertTrue($notification->isCompleted());
    }
}
