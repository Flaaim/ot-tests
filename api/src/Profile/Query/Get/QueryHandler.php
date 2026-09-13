<?php

declare(strict_types=1);

namespace App\Profile\Query\Get;

use App\Profile\Query\ProfileFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly ProfileFetcherInterface $users,
    ) {}

    public function handle(Query $query): ProfileDTO
    {
        $profile = $this->users->getProfile($query->userId);

        if (empty($profile)) {
            throw new DomainException('User profile not found.');
        }

        return ProfileDTO::fromArray($profile);
    }
}
