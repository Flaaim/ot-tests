<?php

declare(strict_types=1);

namespace Tests\Functional\Auth\Remove;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\MessageHandler\Profile\ProfileRemovedHandler;
use App\Profile\Event\ProfileRemoved;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Functional\FixturesLoader;

/**
 * @internal
 * @coversNothing
 */
final class ProfileRemovedHandlerTest extends KernelTestCase
{
    private readonly UserRepository $users;
    private readonly ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = self::getContainer();

        $fixtures = new FixturesLoader($this->container);
        $fixtures->loadFixtures([RequestFixture::class]);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $this->users = new UserRepository($em);
    }

    public function testRemove(): void
    {
        $handler = $this->container->get(ProfileRemovedHandler::class);
        $event = new ProfileRemoved(RequestFixture::USER_ID);

        $handler($event);
        self::assertNull($this->users->findById(new Id(RequestFixture::USER_ID)));
    }
}
