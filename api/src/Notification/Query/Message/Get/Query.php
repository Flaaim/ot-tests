<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\Get;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $profileId,
        #[Assert\Positive]
        public int $page = 1,
        #[Assert\Positive]
        public int $limit = 5,
    ) {}
}
