<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\GetPaginated;

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

    private string $adminToken;
    private string $userToken;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();
        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

        $this->adminToken = $this->getAccessToken(
            $this->client,
            RoleFixture::ADMIN_EMAIL,
            RoleFixture::ADMIN_PASSWORD,
        );
        $this->userToken = $this->getAccessToken(
            $this->client,
            RoleFixture::USER_EMAIL,
            RoleFixture::USER_PASSWORD,
        );
    }

    public function testUnauthenticatedReturn401(): void
    {
        $this->client->jsonRequest('GET', '/v1/admin/notifications');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testForbiddenToRegularUser(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/admin/notifications',
            [],
            $this->authHeaders($this->userToken)
        );
    }

    public function testSuccess(): void
    {
        $this->client->catchExceptions(false);
        $this->client->jsonRequest(
            'GET',
            '/v1/admin/notifications',
            [],
            $this->authHeaders($this->adminToken)
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($data = $this->client->getResponse()->getContent());

        $data = Json::decode($data);

        self::assertArrayHasKey('totalCount', $data);
        self::assertArrayHasKey('totalPages', $data);
        self::assertArrayHasKey('items', $data);

        self::assertCount(1, $data['items']);

        $notification = $data['items'][0];

        self::assertArrayHasKey('notificationId', $notification);
        self::assertArrayHasKey('subject', $notification);
        self::assertArrayHasKey('status', $notification);
        self::assertArrayHasKey('createdAt', $notification);
        self::assertArrayHasKey('countMessages', $notification);
        self::assertArrayHasKey('unreadMessages', $notification);
    }
}
