<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Profile\Remove\Handler;

use App\Notification\Entity\Message\Message;
use App\Notification\MessageHandler\Profile\ProfileRemovedHandler;
use App\Profile\Event\ProfileRemoved;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Functional\FixturesLoader;

/**
 * @internal
 * @coversNothing
 */
final class ProfileRemoveHandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = $this->getContainer();

        $fixtureLoader = new FixturesLoader($this->container);
        $fixtureLoader->loadFixtures([RequestFixture::class]);
    }

    public function testRemove(): void
    {
        $message = new ProfileRemoved(RequestFixture::PROFILE_ID);
        $handler = $this->container->get(ProfileRemovedHandler::class);

        $handler($message);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $messageCount = $em->getRepository(Message::class)->count(['profileId' => RequestFixture::PROFILE_ID]);

        self::assertEquals(0, $messageCount);
    }
}
