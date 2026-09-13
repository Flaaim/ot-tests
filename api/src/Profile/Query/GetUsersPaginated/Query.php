<?php

declare(strict_types=1);

namespace App\Profile\Query\GetUsersPaginated;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Query
{
    public function __construct(
        #[Assert\GreaterThan(0)]
        public int $page = 1,
        #[Assert\GreaterThan(0)]
        public int $limit = 15,
    ) {}
}
