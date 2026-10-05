<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Notification\Remove;

use App\Notification\Entity\Message\Message;
use App\Notification\Entity\Notification\Notification;
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
        $this->client->jsonRequest('DELETE', '/v1/admin/notifications/' . RequestFixture::NOTIFICATION_ID);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testForbiddenToRegularUser(): void
    {
        $this->client->jsonRequest(
            'DELETE',
            '/v1/admin/notifications/' . RequestFixture::NOTIFICATION_ID,
            [],
            $this->authHeaders($this->userToken)
        );
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'DELETE',
            '/v1/admin/notifications/' . RequestFixture::NOTIFICATION_ID,
            [
                'subject' => 'subject',
                'message' => 'message',
            ],
            $this->authHeaders($this->adminToken)
        );

        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);

        $notificationCount = $em->getRepository(Notification::class)
            ->count(['notificationId' => RequestFixture::NOTIFICATION_ID]);

        self::assertEquals(0, $notificationCount);

        $messageCount = $em->getRepository(Message::class)
            ->count();

        self::assertEquals(0, $messageCount);
    }

    public function testInvalid(): void
    {
        $this->client->jsonRequest(
            'DELETE',
            '/v1/admin/notifications/invalid',
            [],
            $this->authHeaders($this->adminToken)
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'notificationId' => 'This is not a valid UUID.',
        ]], $data);
    }
}
