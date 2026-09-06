<?php

declare(strict_types=1);

namespace Tests\Functional\Admin\Testing\Category\Rename;

use App\Testing\Entity\Category\CategoryId;
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
        $this->client->jsonRequest('PUT', '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID . '/rename');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testForbiddenForRegularUsers(): void
    {
        $this->client->jsonRequest(
            'PUT',
            '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID . '/rename',
            [],
            $this->authHeaders($this->userToken),
        );

        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'PUT',
            '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID . '/rename',
            [
                'id' => RequestFixture::CATEGORY_ID,
                'name' => 'Новое имя',
            ],
            $this->authHeaders($this->adminToken),
        );

        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        $category = $this->categories->getById(new CategoryId(RequestFixture::CATEGORY_ID));

        self::assertEquals('Новое имя', $category->getName());
        self::assertEquals('novoe-ima', $category->getSlug());
    }

    public function testSlugExists(): void
    {
        $this->client->jsonRequest(
            'PUT',
            '/v1/admin/testing/categories/' . RequestFixture::CATEGORY_ID . '/rename',
            [
                'id' => RequestFixture::CATEGORY_ID,
                'name' => RequestFixture::CATEGORY_EXISTS_NAME,
            ],
            $this->authHeaders($this->adminToken),
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Category with this slug already exists.',
        ], $data);
    }
}
