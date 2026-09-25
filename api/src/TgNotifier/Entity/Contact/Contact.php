<?php

declare(strict_types=1);

namespace App\TgNotifier\Entity\Contact;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'telegram_chats')]
final class Contact
{
    public function __construct(
        #[Id]
        #[Column(type: 'contact_id', unique: true)]
        private ContactId $id,
        #[Column(type: 'string', length: 55)]
        private string $name,
        #[Column(type: 'string', length: 55, unique: true)]
        private string $chatId,
        #[Column(type: 'datetime_immutable')]
        private DateTimeImmutable $createdAt,
    ) {}

    public function getContactId(): ContactId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
