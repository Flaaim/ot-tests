<?php

declare(strict_types=1);

namespace App\Notification\Command\Notification\SendPersonal;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $profileId,
        #[Assert\Length(min: 3, max: 100)]
        public string $subject,
        #[Assert\Length(min: 3, max: 10000)]
        public string $message,
    ) {}
}
