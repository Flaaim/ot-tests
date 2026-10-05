<?php

declare(strict_types=1);

namespace App\Notification\Command\Launch;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $subject,
        #[Assert\NotBlank]
        public string $message,
    ) {}
}
