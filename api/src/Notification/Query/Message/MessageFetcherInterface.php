<?php

declare(strict_types=1);

namespace App\Notification\Query\Message;

interface MessageFetcherInterface
{
    public function getUnreadCount(string $profileId): int;
}
