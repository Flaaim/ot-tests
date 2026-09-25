<?php

declare(strict_types=1);

namespace Tests\Functional\TgNotifier\ParseChats\Handler;

use App\TgNotifier\Entity\DTO\ChatDTO;
use App\TgNotifier\Event\ChatsParsed;
use App\TgNotifier\MessageHandler\ChatsParsedHandler;
use App\TgNotifier\Query\Contact\ContactFetcher;
use App\TgNotifier\Query\Contact\ContactFetcherInterface;
use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ChatsParsedHandlerTest extends KernelTestCase
{
    private readonly ContainerInterface $container;
    private readonly ContactFetcherInterface $contacts;
    public function setUp(): void
    {
        self::bootKernel();
        $this->container = self::getContainer();

        $conn = $this->container->get(Connection::class);
        $this->contacts = new ContactFetcher($conn);
    }

    public function testSuccess(): void
    {
        $handler = $this->container->get(ChatsParsedHandler::class);
        $message = new ChatsParsed([
            new ChatDTO('3c733c71-1255-4952-b1c0-b657c85bf610', 'name', '12345678', '2026-09-25')
        ]);
        $handler($message);

        self::assertCount(1, $this->contacts->getAll());
    }
}
