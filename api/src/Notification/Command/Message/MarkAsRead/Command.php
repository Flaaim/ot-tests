<?php

declare(strict_types=1);

namespace App\Notification\Command\Message\MarkAsRead;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $messageId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $profileId
    ) {}
}
