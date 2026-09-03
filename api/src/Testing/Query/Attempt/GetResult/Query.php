<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $attemptId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $userId,
    ) {}
}
