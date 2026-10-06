<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\GetLatest;

use App\Notification\Query\Message\MessageFetcherInterface;
use DomainException;

final readonly class QueryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageFetcherInterface $messages
    ) {}

    public function handle(Query $query): array
    {
        $result = $this->messages->getLatestMessages($query->profileId, $query->limit);

        if (empty($result)) {
            throw new DomainException('Сообщения отсутствуют.');
        }

        return array_map(
            static fn (array $message): MessageDTO => MessageDTO::fromArray($message),
            $result
        );
    }
}
