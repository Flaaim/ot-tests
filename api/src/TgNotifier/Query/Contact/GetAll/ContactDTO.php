<?php

declare(strict_types=1);

namespace App\TgNotifier\Query\Contact\GetAll;

final readonly class ContactDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $chatId
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            chatId: $data['chat_id']
        );
    }
}
