<?php

declare(strict_types=1);

namespace Tests\Functional\Profile\ChangePersonalData;

use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Entity\Profile\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Random\Randomizer;
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
    private readonly ProfileRepository $profiles;
    private readonly string $userToken;

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
        $this->profiles = new ProfileRepository($em);

        $this->userToken = $this->getAccessToken(
            $this->client,
            RequestFixture::USER_EMAIL,
            RequestFixture::USER_PASSWORD,
        );
    }

    public function testUnauthenticatedReturns401(): void
    {
        $this->client->jsonRequest('PUT', '/v1/me/change-personal-data');
        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->jsonRequest(
            'PUT',
            '/v1/me/change-personal-data',
            [
                'name' => 'Александр',
                'surname' => 'Григорьев',
            ],
            $this->authHeaders($this->userToken)
        );
        self::assertEquals(204, $this->client->getResponse()->getStatusCode());

        $profile = $this->profiles->get(new ProfileId(RequestFixture::USER_ID));
        self::assertNotNull($profile->getName());
        self::assertNotNull($profile->getSurname());
    }

    public function testEmpty(): void
    {
        $this->client->jsonRequest('PUT', '/v1/me/change-personal-data', [], $this->authHeaders($this->userToken));

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'name' => 'This value is too short. It should have 1 character or more.',
            'surname' => 'This value is too short. It should have 1 character or more.',
        ]], $data);
    }

    public function testInvalid(): void
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomizer = new Randomizer();
        $name = $randomizer->getBytesFromString($chars, RequestFixture::NAME_LENGTH);
        $surname = $randomizer->getBytesFromString($chars, RequestFixture::SURNAME_LENGTH);
        $this->client->jsonRequest(
            'PUT',
            '/v1/me/change-personal-data',
            [
                'name' => $name,
                'surname' => $surname,
            ],
            $this->authHeaders($this->userToken)
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());

        self::assertJson($body = $this->client->getResponse()->getContent());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'name' => 'This value is too long. It should have 55 characters or less.',
            'surname' => 'This value is too long. It should have 75 characters or less.',
        ]], $data);
    }
}
