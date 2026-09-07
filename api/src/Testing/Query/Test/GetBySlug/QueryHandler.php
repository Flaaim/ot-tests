<?php

declare(strict_types=1);

namespace App\Testing\Query\Test\GetBySlug;

use App\Testing\Query\Test\TestFetcherInterface;
use DomainException;

final class QueryHandler
{
    public function __construct(
        private readonly TestFetcherInterface $tests
    ) {}

    public function handle(Query $query): TestDTO
    {
        $row = $this->tests->getBySlug($query->slug);
        if (empty($row)) {
            throw new DomainException('No test found for slug ' . $query->slug);
        }
        return TestDTO::fromArray($row);
    }
}
