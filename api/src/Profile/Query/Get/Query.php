<?php

declare(strict_types=1);

namespace App\Profile\Query\Get;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $userId
    ) {}
}
