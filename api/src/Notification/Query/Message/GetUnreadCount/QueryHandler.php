<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\GetUnreadCount;

use App\Notification\Query\Message\MessageFetcherInterface;

final readonly class QueryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageFetcherInterface $messages
    ) {}

    public function handle(Query $query): int
    {
        return $this->messages->getUnreadCount($query->profileId);
    }
}
