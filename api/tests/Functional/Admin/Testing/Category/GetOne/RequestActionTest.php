<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Testing\Category\GetOne;

use App\Testing\Entity\Category\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private readonly CategoryRepository $categories;
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

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $this->categories = new CategoryRepository($em);

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
        $this->client->jsonRequest('GET', '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testForbiddenForRegularUsers(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID,
            [],
            $this->authHeaders($this->userToken),
        );

        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_PARENT_ID,
            [],
            $this->authHeaders($this->adminToken),
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            'id' => RequestFixture::CATEGORY_PARENT_ID,
            'name' => RequestFixture::CATEGORY_PARENT_NAME,
            'description' => 'Category description',
            'slug' => 'drugoe-nazvanie',
            'children' => [
                [
                    'id' => RequestFixture::CATEGORY_ID,
                    'name' => RequestFixture::CATEGORY_NAME,
                    'description' => 'Category description',
                    'slug' => 'ohrana-truda',
                    'parentId' => RequestFixture::CATEGORY_PARENT_ID,
                    'children' => [],
                ],
            ],
            'parentId' => null,
        ], $data);
    }
}
