<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\GetByUser;

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
    private readonly KernelBrowser $client;
    private readonly ContainerInterface $container;
    private string $userToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();

        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

        $this->userToken = $this->getAccessToken(
            $this->client,
            RequestFixture::USER_EMAIL,
            RequestFixture::USER_PASSWORD,
        );
    }

    public function testUnauthenticatedReturns401(): void
    {
        $this->client->jsonRequest('GET', '/v1/user/attempts');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/user/attempts?page=1&limit=10',
            [],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertArrayHasKey('totalCount', $data);
        self::assertArrayHasKey('totalPages', $data);
        self::assertArrayHasKey('items', $data);

        self::assertCount(1, $data['items']);

        self::assertArrayHasKey('id', $data['items'][0]);
        self::assertArrayHasKey('status', $data['items'][0]);
        self::assertArrayHasKey('score', $data['items'][0]);
        self::assertArrayHasKey('mistakes', $data['items'][0]);
        self::assertArrayHasKey('startedAt', $data['items'][0]);
        self::assertArrayHasKey('ticketNumber', $data['items'][0]);
        self::assertArrayHasKey('name', $data['items'][0]);
        self::assertArrayHasKey('cipher', $data['items'][0]);
        self::assertArrayHasKey('allowedMistakes', $data['items'][0]);
        self::assertArrayHasKey('finishedAt', $data['items'][0]);
    }
}
