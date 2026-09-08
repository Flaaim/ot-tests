<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\GetByUser;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\FixturesLoader;
use Tests\Functional\Json;
use Tests\Functional\OAuthTokenTrait;
use Tests\Functional\Testing\Attempt\Get\RequestFixture;

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
            '/v1/user/attempts',
            [],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertArrayHasKey('id', $data[0]);
        self::assertArrayHasKey('status', $data[0]);
        self::assertArrayHasKey('score', $data[0]);
        self::assertArrayHasKey('mistakes', $data[0]);
        self::assertArrayHasKey('startedAt', $data[0]);
        self::assertArrayHasKey('ticketNumber', $data[0]);
        self::assertArrayHasKey('name', $data[0]);
        self::assertArrayHasKey('cipher', $data[0]);
        self::assertArrayHasKey('allowedMistakes', $data[0]);
        self::assertArrayHasKey('finishedAt', $data[0]);
    }
}
