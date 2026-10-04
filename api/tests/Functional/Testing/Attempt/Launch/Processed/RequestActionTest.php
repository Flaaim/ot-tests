<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\Launch\Processed;

use App\Testing\Entity\Attempt\Answer;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;
use App\Testing\Event\Attempt\TimeoutAttemptCommand;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Tests\Functional\FixturesLoader;
use Tests\Functional\Json;
use Tests\Functional\OAuthTokenTrait;

/**
 * @internal
 * @coversNothing
 */
final class RequestActionTest extends WebTestCase
{
    use OAuthTokenTrait;
    private readonly KernelBrowser $client;
    private readonly ContainerInterface $container;
    private string $userToken;
    private EntityManagerInterface $em;
    private AttemptRepository $attempts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();

        /** @var EntityManagerInterface $em */
        $this->em = $this->container->get(EntityManagerInterface::class);
        $this->attempts = new AttemptRepository($this->em);

        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

        $this->userToken = $this->getAccessToken(
            $this->client,
            RequestFixture::USER_EMAIL,
            RequestFixture::USER_PASSWORD,
        );
    }

    public function testProcessedAttempt(): void
    {
        /** @var InMemoryTransport $transport */
        $transport = $this->client->getContainer()->get('messenger.transport.async');
        $transport->reset();

        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts',
            [
                'testId' => RequestFixture::TEST_ID,
                'ticketNumber' => RequestFixture::TICKET_NUMBER,
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(RequestFixture::ATTEMPT_ID, $data['attemptId']);

        $attempt = $this->attempts->get(new AttemptId($data['attemptId']));
        self::assertEquals(0, $attempt->getMistakes());
        self::assertEquals(0, $attempt->getScore());
        self::assertEquals(new DateTimeImmutable()->format('Y-m-d H:i'), $attempt->getStartedAt()->format('Y-m-d H:i'));

        self::assertTrue($this->isAnswersIsClear());

        self::assertCount(1, $transport->getSent());

        $message = $transport->getSent()[0]->getMessage();
        self::assertInstanceOf(TimeoutAttemptCommand::class, $message);

        self::assertNotNull($message->attemptId);
        self::assertEquals(
            $attempt->getStartedAt()->format('Y-m-d H:i:s'),
            $message->startedAt->format('Y-m-d H:i:s')
        );
    }

    private function isAnswersIsClear(): bool
    {
        $result = $this->em->createQueryBuilder()
            ->from(Answer::class, 'a')
            ->select('COUNT(a.id)')
            ->where('a.attempt = :attempt')
            ->setParameter('attempt', RequestFixture::ATTEMPT_ID)
            ->getQuery()
            ->getSingleScalarResult();

        return 0 === (int)$result;
    }
}
