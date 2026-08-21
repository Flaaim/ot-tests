<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt;

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
        $this->client->jsonRequest('POST', '/v1/testing/attempts');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
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

        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('status', $data);
        self::assertArrayHasKey('ticketNumber', $data);
        self::assertArrayHasKey('questions', $data);
    }

    public function testNotFound(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts',
            [
                'testId' => RequestFixture::TEST_NOT_FOUND,
                'ticketNumber' => RequestFixture::TICKET_NUMBER,
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Test not found.',
        ], $data);
    }

    public function testTicketNumberNotFound(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts',
            [
                'testId' => RequestFixture::TEST_ID,
                'ticketNumber' => 6,
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Invalid ticket number.',
        ], $data);
    }

    public function testInvalid(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts',
            [
                'testId' => 'invalid',
                'ticketNumber' => 'invalid',
            ],
            $this->authHeaders($this->userToken)
        );
        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'testId' => 'This is not a valid UUID.',
            'ticketNumber' => 'This value should be greater than 0.',
        ]], $data);
    }
}
