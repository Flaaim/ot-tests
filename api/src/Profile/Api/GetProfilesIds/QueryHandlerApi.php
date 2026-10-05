<?php

declare(strict_types=1);

namespace App\Profile\Api\GetProfilesIds;

use App\Profile\Query\GetProfileIds\QueryHandler;
use Generator;

final readonly class QueryHandlerApi
{
    /** @psalm-suppress PossiblyUnusedMethod  */
    public function __construct(
        private QueryHandler $queryHandler,
    ) {}

    public function getProfileIds(): Generator
    {
        return $this->queryHandler->handle();
    }
}
