<?php

declare(strict_types=1);

namespace Tests\Functional\OAuth\Magic;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functional\ArraySubsetAssertTrait;
use Tests\Functional\FixturesLoader;
use Tests\Functional\Json;

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

    public function testInvalidClient(): void
    {
        $this->client->request('POST', '/token', [
            'grant_type' => 'magic',
            'client_id' => 'invalid',
            'client_secret' => 'my-super-secret-123',
            'email' => AuthorizeFixture::USER_EMAIL,
            'token' => AuthorizeFixture::USER_TOKEN,
        ]);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string)$this->client->getResponse()->getContent());

        $data = Json::decode($content);

        self::assertArraySubset([
            'error' => 'invalid_client',
        ], $data);
    }

    public function testSuccess(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::USER_EMAIL,
                'token' => AuthorizeFixture::USER_TOKEN,
            ]
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($content = (string)$this->client->getResponse()->getContent());

        $data = Json::decode($content);

        self::assertArraySubset([
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ], $data);
    }

    public function testSuccessGuest(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::GUEST_EMAIL,
                'token' => AuthorizeFixture::GUEST_TOKEN,
                'guestId' => AuthorizeFixture::GUEST_ID,
            ]
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($content = (string)$this->client->getResponse()->getContent());

        $data = Json::decode($content);

        self::assertArraySubset([
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ], $data);
    }

    public function testAlreadyConfirmed(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::ALREADY_EMAIL,
                'token' => AuthorizeFixture::ALREADY_TOKEN,
            ]
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'error' => 'invalid_grant',
            'hint' => 'Incorrect magicLinkToken.',
        ], $data);
    }

    public function testElseEmail(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::GUEST_EMAIL,
                'token' => AuthorizeFixture::USER_TOKEN,
                'guestId' => AuthorizeFixture::GUEST_ID,
            ]
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'error' => 'invalid_grant',
            'hint' => 'Incorrect magicLinkEmail.',
        ], $data);
    }

    public function testExpired(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::EXPIRED_EMAIL,
                'token' => AuthorizeFixture::EXPIRED_TOKEN,
            ]
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'error' => 'invalid_grant',
            'hint' => 'Token is expired.',
        ], $data);
    }

    public function testTokenEmpty(): void
    {
        $this->client->request(
            'POST',
            '/token',
            [
                'grant_type' => 'magic',
                'client_id' => 'frontend',
                'client_secret' => 'my-super-secret-123',
                'email' => AuthorizeFixture::USER_EMAIL,
                'token' => '',
            ]
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = (string)$this->client->getResponse()->getContent());
        $data = Json::decode($body);

        self::assertArraySubset([
            'error' => 'invalid_request',
        ], $data);
    }
}
