<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\Launch\Handler;

use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;
use App\Testing\Entity\Attempt\Status;
use App\Testing\Event\Attempt\TimeoutAttemptCommand;
use App\Testing\MessageHandler\Attempt\TimeoutAttemptHandler;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tests\Functional\FixturesLoader;

/**
 * @internal
 * @coversNothing
 */
final class TimeoutAttemptHandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;
    private readonly AttemptRepository $attempts;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->container = self::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $this->attempts = new AttemptRepository($em);

        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);
    }

    public function testSuccess(): void
    {
        $handler = $this->container->get(TimeoutAttemptHandler::class);
        $message = new TimeoutAttemptCommand(RequestFixture::ATTEMPT_ID);

        $handler($message);

        $attempt = $this->attempts->get(new AttemptId(RequestFixture::ATTEMPT_ID));

        self::assertEquals(Status::STATUS_TIMEOUT, $attempt->getStatus()->getValue());
        self::assertEquals(new DateTimeImmutable()->format('Y-m-d'), $attempt->getFinishedAt()->format('Y-m-d'));
    }
}
