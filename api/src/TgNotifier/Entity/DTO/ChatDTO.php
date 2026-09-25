<?php

declare(strict_types=1);

namespace App\TgNotifier\Entity\DTO;

final class ChatDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $chatId,
        public string $date
    ) {}
}
