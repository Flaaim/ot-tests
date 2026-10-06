<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\MarkAllAsRead;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Message\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\FixturesLoader;
use Tests\Functional\OAuthTokenTrait;

/**
 * @internal
 * @coversNothing
 */
final class RequestActionTest extends WebTestCase
{
    use OAuthTokenTrait;
    private KernelBrowser $client;
    private readonly ContainerInterface $container;
    private readonly MessageRepository $messages;
    private string $johnToken;
    private string $aliceToken;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();
        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

        $em = $this->container->get(EntityManagerInterface::class);
        $this->messages = new MessageRepository($em);

        $this->johnToken = $this->getAccessToken(
            $this->client,
            ProfileFixture::JOHN_EMAIL,
            ProfileFixture::PASSWORD
        );

        $this->aliceToken = $this->getAccessToken(
            $this->client,
            ProfileFixture::ALICE_EMAIL,
            ProfileFixture::PASSWORD
        );
    }

    public function testUnauthenticatedReturn401(): void
    {
        $this->client->jsonRequest('PATCH', '/v1/messages/read-all');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testReadAllJohn(): void
    {
        $this->client->jsonRequest(
            'PATCH',
            '/v1/messages/read-all',
            [],
            $this->authHeaders($this->johnToken)
        );

        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        $em = $this->container->get(EntityManagerInterface::class);
        $messages = $em->getRepository(Message::class)->findAll();

        self::assertCount(3, $messages);

        $readMessages = array_filter($messages, static fn (Message $message): bool => $message->isRead());
        self::assertCount(2, $readMessages);
    }

    public function testReadAllAlice(): void
    {
        $this->client->jsonRequest(
            'PATCH',
            '/v1/messages/read-all',
            [],
            $this->authHeaders($this->aliceToken)
        );

        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        $em = $this->container->get(EntityManagerInterface::class);
        $messages = $em->getRepository(Message::class)->findAll();

        self::assertCount(3, $messages);

        $readMessages = array_filter($messages, static fn (Message $message): bool => $message->isRead());
        self::assertCount(1, $readMessages);
    }
}
