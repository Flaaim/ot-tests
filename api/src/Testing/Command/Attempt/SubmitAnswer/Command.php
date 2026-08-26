<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\SubmitAnswer;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $attemptId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $questionId,
        #[Assert\Count(min: 1)]
        public readonly array $selectedAnswersIds
    ) {}
}
