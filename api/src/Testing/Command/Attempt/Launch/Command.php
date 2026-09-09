<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\Launch;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $testId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $userId,
        #[Assert\GreaterThan(0)]
        public readonly int $ticketNumber,
    ) {}
}
