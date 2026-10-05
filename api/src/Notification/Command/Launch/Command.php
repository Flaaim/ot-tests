<?php

declare(strict_types=1);

namespace App\Notification\Command\Launch;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\Length(min: 3, max: 100)]
        public string $subject,
        #[Assert\Length(min: 3, max: 10000)]
        public string $message,
    ) {}
}
