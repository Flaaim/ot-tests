<?php

declare(strict_types=1);

namespace Tests\Functional\OAuth\Social;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\ArraySubsetAssertTrait;
use Tests\Functional\FixturesLoader;
use Tests\Functional\Json;
use Tests\Functional\OAuth\Password\AuthorizeFixture;

/**
 * @internal
 * @coversNothing
 */
final class AuthorizeTest extends WebTestCase
{
    use ArraySubsetAssertTrait;
    private readonly KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();

        $container = $this->client->getContainer();

        $fixtures = new FixturesLoader($container);
        $fixtures->loadFixtures([AuthorizeFixture::class]);
    }

    public function testSocialAuthSuccess(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'social',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'network' => 'google',
                'code' => 'new-code-identity',
                'redirect_uri' => 'http://localhost/callback',
            ]
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ], $data);

        self::assertArrayHasKey('access_token', $data);
        self::assertArrayHasKey('refresh_token', $data);
    }

    public function testSocialAuthEmailConflict(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'social',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'network' => 'google',
                'code' => 'conflict',
                'redirect_uri' => 'http://localhost/callback',
            ]
        );
        self::assertEquals(400, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'hint' => 'Пользователь с таким email уже существует. Войдите обычным способом и привяжите аккаунт в настройках профиля.',
        ], $data);
    }
}
