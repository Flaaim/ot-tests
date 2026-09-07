<?php

declare(strict_types=1);

namespace App\Testing\Query\Test\GetByCategory;

use App\Testing\Query\Test\TestFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly TestFetcherInterface $tests
    ) {}

    public function handle(Query $query): array
    {
        $rows = $this->tests->getAllBySlug($query->slug);
        if (empty($rows)) {
            throw new DomainException('Tests by category slug not found.');
        }

        return array_map(
            static fn (array $row) => TestDTO::fromArray($row),
            $rows
        );
    }
}
