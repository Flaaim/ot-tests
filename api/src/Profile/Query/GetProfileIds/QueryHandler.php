<?php

declare(strict_types=1);

namespace App\Profile\Query\GetProfileIds;

use App\Profile\Query\ProfileFetcherInterface;
use Generator;

final readonly class QueryHandler
{
    public function __construct(
        private ProfileFetcherInterface $profiles
    ) {}

    public function handle(): Generator
    {
        return $this->profiles->getProfileIds();
    }
}
