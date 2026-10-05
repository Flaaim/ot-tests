<?php

declare(strict_types=1);

namespace App\Profile\Query\HasProfiles;

use App\Profile\Query\ProfileFetcherInterface;

final readonly class QueryHandler
{
    public function __construct(
        private ProfileFetcherInterface $profiles
    ) {}

    public function handle(): bool
    {
        return $this->profiles->hasProfiles();
    }
}
