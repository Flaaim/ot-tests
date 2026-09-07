<?php

declare(strict_types=1);

namespace Tests\Functional\Testing\Category\GetTree;

use Psr\Container\ContainerInterface;
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
    private readonly ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->container = $this->client->getContainer();

        $fixturesLoader = new FixturesLoader($this->container);
        $fixturesLoader->loadFixtures([RequestFixture::class]);
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'GET',
            '/v1/testing/categories',
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals([
            [
                'id' => RequestFixture::CATEGORY_PARENT_ID,
                'name' => RequestFixture::CATEGORY_PARENT_NAME,
                'slug' => 'roditel-skaa-kategoria',
                'parentId' => null,
                'description' => 'Category description',
                'children' => [
                    [
                        'id' => RequestFixture::CATEGORY_ID,
                        'name' => RequestFixture::CATEGORY_NAME,
                        'slug' => 'docernaa-kategoria',
                        'parentId' => RequestFixture::CATEGORY_PARENT_ID,
                        'children' => [],
                        'description' => 'Category description',
                    ],
                ],
            ],
        ], $data);
    }
}
