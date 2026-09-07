<?php

declare(strict_types=1);

namespace App\Testing\Query\Test\GetBySlug;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    public function __construct(
        #[Assert\NotBlank]
        public string $slug,
    ) {}
}
