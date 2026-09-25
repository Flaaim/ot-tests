<?php

declare(strict_types=1);

namespace App\TgNotifier\Test\Unit\Entity;

use App\TgNotifier\Entity\Contact\Contact;
use App\TgNotifier\Entity\Contact\ContactId;
use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{
    public function testCreate(): void
    {
        $contact = new Contact(
             $contactId = ContactId::generate(),
            $name = 'Alexandr',
            $chatId = '1234578',
            $date = new \DateTimeImmutable(),
        );

        self::assertEquals($contactId, $contact->getContactId());
        self::assertEquals($name, $contact->getName());
        self::assertEquals($chatId, $contact->getChatId());
        self::assertEquals($date, $contact->getCreatedAt());

    }
}
