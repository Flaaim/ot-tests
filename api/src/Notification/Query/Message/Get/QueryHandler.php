<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\Get;

use App\Notification\Query\Message\MessageFetcherInterface;

final readonly class QueryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageFetcherInterface $messages
    ) {}

    public function handle(Query $query): ListMessageDTO
    {
        $safeLimit = max(1, $query->limit);

        $result = $this->messages->get($query->profileId, $query->page, $query->limit);

        $items = array_map(
            static fn (array $message): MessageDTO => MessageDTO::fromArray($message),
            $result['items']
        );

        $totalCount = $result['totalCount'];

        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $safeLimit) : 0;

        return new ListMessageDTO(
            items: $items,
            totalCount: $totalCount,
            totalPages: $totalPages,
        );
    }
}
