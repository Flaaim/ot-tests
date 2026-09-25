<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\TgNotifier\ParseChats;

use App\TgNotifier\Query\Contact\ContactFetcher;
use App\TgNotifier\Query\Contact\ContactFetcherInterface;
use Doctrine\DBAL\Connection;
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
    private readonly ContactFetcherInterface $contacts;
    private string $adminToken;
    private string $userToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();

        $container = $this->client->getContainer();

        $fixturesLoader = new FixturesLoader($container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);

        /** @var Connection $conn */
        $conn = $container->get(Connection::class);
        $this->contacts = new ContactFetcher($conn);

        $this->adminToken = $this->getAccessToken(
            $this->client,
            RequestFixture::ADMIN_EMAIL,
            RequestFixture::ADMIN_PASSWORD,
        );

        $this->userToken = $this->getAccessToken(
            $this->client,
            RequestFixture::USER_EMAIL,
            RequestFixture::USER_PASSWORD,
        );
    }

    public function testUnauthenticatedReturns401(): void
    {
        $this->client->jsonRequest('POST', '/v1/admin/contacts');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testForbiddenForRegularUser(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/admin/contacts',
            [],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $yamlContent = "- \n  id: 217\n  chat_id: 5286109490\n  name: \"Вера\"\n  date: \"2025-03-10 07:06:48\"";
        $this->client->jsonRequest(
            'POST',
            '/v1/admin/contacts',
            [
                'text' => $yamlContent,
            ],
            $this->authHeaders($this->adminToken)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());

        $contacts = $this->contacts->getAll();

        self::assertCount(1, $contacts);
        self::assertArrayHasKey('id', $contacts[0]);
        self::assertArrayHasKey('name', $contacts[0]);
        self::assertArrayHasKey('chat_id', $contacts[0]);
    }

    public function testEmpty(): void
    {
        $this->client->jsonRequest('POST', '/v1/admin/contacts', [], $this->authHeaders($this->adminToken));

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'text' => 'This value should not be blank.',
        ]], $data);
    }

    public function testInvalid(): void
    {
        $this->client->jsonRequest(
            'POST',
            '/v1/admin/contacts',
            [
                'text' => 'some string',
            ],
            $this->authHeaders($this->adminToken)
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertEquals(['message' => 'Can not parse YAML.'], $data);
    }
}
