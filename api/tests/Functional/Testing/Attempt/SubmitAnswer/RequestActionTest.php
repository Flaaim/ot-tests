<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\SubmitAnswer;

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
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts/' . RequestFixture::TEST_ID. '/sumbit'
        );
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts/' . RequestFixture::ATTEMPT_ID . '/submit',
            [
                'questionId' => '90be077454a14f3d965c4b07645e3769',
                'selectedAnswersIds' => ['93ff5fdd3e7eeb5cc38696beac126968'],
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());

    }

    public function testNotFound(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts/' . RequestFixture::ATTEMPT_ID_NOT_FOUND . '/submit',
            [
                'questionId' => '90be077454a14f3d965c4b07645e3769',
                'selectedAnswersIds' => ['93ff5fdd3e7eeb5cc38696beac126968'],
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Attempt not found.',
        ], $data);
    }
    public function testEmpty(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts/' . RequestFixture::ATTEMPT_ID . '/submit',
            [],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'questionId' => 'This value should not be blank.',
            'selectedAnswersIds' => 'This collection should contain 1 element or more.',
        ]], $data);
    }
    public function testInvalid(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/testing/attempts/' . RequestFixture::ATTEMPT_ID . '/submit',
            [
                'questionId' => 'invalid',
                'selectedAnswersIds' => ['invalid'],
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'questionId' => 'This ',
        ]], $data);
    }
}
