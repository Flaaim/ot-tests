<?php

declare(strict_types=1);

namespace App\Profile\Query\GetFull;

use App\Profile\Query\ProfileFetcherInterface;
use DomainException;

final readonly class QueryHandler
{
    public function __construct(
        private ProfileFetcherInterface $profiles
    ) {}

    public function handle(Query $query): ProfileFull
    {
        $result = $this->profiles->getFullProfile($query->id);

        if (empty($result)) {
            throw new DomainException('Full profile not found.');
        }

        return ProfileFull::fromArray($result);
    }
}
