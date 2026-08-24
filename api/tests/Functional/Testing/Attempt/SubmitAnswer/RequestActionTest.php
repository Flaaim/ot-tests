<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Attempt\SubmitAnswer;

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

    public function testSuccess(): void
    {
        $this->client->catchExceptions(false);
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
}
