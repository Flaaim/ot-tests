<?php

declare(strict_types=1);

namespace App\Admin\Stats\Query\PopularTests;

use App\Admin\Stats\Query\StatsFetcherInterface;
use DomainException;

final readonly class QueryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private StatsFetcherInterface $fetcher,
    ) {}

    public function handle(): array
    {
        $result = $this->fetcher->getPopularTestsStats();
        if (empty($result)) {
            throw new DomainException('Can not fetch popular attempts.');
        }

        return array_map(static fn (array $row) => PopularTestsDTO::fromArray($row), $result);
    }
}
