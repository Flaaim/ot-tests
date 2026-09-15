<?php

declare(strict_types=1);

namespace Tests\Functional\Auth\MessageHandler\AddProfile;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\MessageHandler\Profile\ProfileAddedHandler;
use App\Profile\Event\ProfileAdded;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ProfileAddedHandlerTest extends KernelTestCase
{
    private readonly UserRepository $users;
    private readonly ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->container = self::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $this->users = new UserRepository($em);
    }

    public function testAdd(): void
    {
        $handler = $this->container->get(ProfileAddedHandler::class);
        $message = new ProfileAdded(
            $id = Uuid::uuid4()->toString(),
            $email = 'test@email.com',
            'admin',
            '123456'
        );

        $handler($message);

        $user = $this->users->findById(new Id($id));

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail()->getValue());
    }
}
