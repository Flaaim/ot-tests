<?php

declare(strict_types=1);

namespace App\TgNotifier\Event;

use App\TgNotifier\Entity\DTO\ChatDTO;

final class ChatsParsed
{
    /** @var ChatDTO[] $chats */
    public function __construct(
        public array $chats,
    ) {}
}
