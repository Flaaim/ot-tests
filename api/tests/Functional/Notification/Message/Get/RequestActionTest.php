<?php

declare(strict_types=1);

namespace Tests\Functional\Notification\Message\Get;

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
    private string $johnToken;
    private string $aliceToken;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();
        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

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
        $this->client->jsonRequest('GET', '/v1/messages');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/messages?page=1&limit=5',
            [],
            $this->authHeaders($this->johnToken)
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertArrayHasKey('items', $data);
        self::assertArrayHasKey('totalCount', $data);
        self::assertArrayHasKey('totalPages', $data);

        self::assertCount(1, $data['items']);

        $message = $data['items'][0];
        self::assertArrayHasKey('messageId', $message);
        self::assertArrayHasKey('subject', $message);
        self::assertArrayHasKey('message', $message);
        self::assertArrayHasKey('createdAt', $message);
        self::assertArrayHasKey('status', $message);
    }
}
