<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Test\GetByCategoryPaginated;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\FixturesLoader;
use Tests\Functional\Json;

/**
 * @internal
 * @coversNothing
 */
final class RequestActionTest extends WebTestCase
{
    private readonly KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();

        $container = $this->client->getContainer();

        $fixturesLoader = new FixturesLoader($container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/testing/categories/ohrana-truda/tests?page=1&limit=10'
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertCount(1, $data['items']);
        self::assertEquals([
            'items' => [
                [
                    'id' => RequestFixture::TEST_ACTIVE_ID,
                    'name' => RequestFixture::TEST_ACTIVE_NAME,
                    'slug' => RequestFixture::TEST_ACTIVE_SLUG,
                    'description' => 'Test description',
                    'cipher' => RequestFixture::TEST_ACTIVE_CIPHER,
                    'createdAt' => new DateTimeImmutable()->format('Y-m-d'),
                ],
            ],
            'totalPages' => 1,
            'totalCount' => 1,
        ], $data);
    }
}
