<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message\MarkAsRead;

use App\Notification\Entity\Message\MessageId;
use App\Notification\Entity\Message\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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
        $this->client->jsonRequest('PATCH', '/v1/messages/' . RequestFixture::MESSAGE_ID);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testReadOwnMessage(): void
    {
        $this->client->jsonRequest(
            'PATCH',
            '/v1/messages/' . RequestFixture::MESSAGE_ID,
            [],
            $this->authHeaders($this->johnToken)
        );

        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        $message = $this->messages->get(new MessageId(RequestFixture::MESSAGE_ID));

        self::assertTrue($message->isRead());
    }

    public function testAlienMessage(): void
    {
        $this->client->jsonRequest(
            'PATCH',
            '/v1/messages/' . RequestFixture::MESSAGE_ID,
            [],
            $this->authHeaders($this->aliceToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['message' => 'Вы не можете прочитать чужое уведомление.'], $data);
    }

    public function testInvalid(): void
    {
        $this->client->jsonRequest(
            'PATCH',
            '/v1/messages/invalid',
            [],
            $this->authHeaders($this->johnToken)
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'messageId' => 'This is not a valid UUID.',
        ]], $data);
    }
}
