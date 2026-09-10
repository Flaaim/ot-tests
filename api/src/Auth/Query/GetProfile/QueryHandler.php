<?php

declare(strict_types=1);

namespace App\Auth\Query\GetProfile;

use App\Auth\Query\UserFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly UserFetcherInterface $users,
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
