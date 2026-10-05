<?php

declare(strict_types=1);

namespace App\Profile\Api\HasProfiles;

use App\Profile\Query\HasProfiles\QueryHandler;

final readonly class QueryHandlerApi
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private QueryHandler $queryHandler,
    ) {}

    public function hasProfiles(): bool
    {
        return $this->queryHandler->handle();
    }
}
