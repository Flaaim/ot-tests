<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\GetUnreadCount;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $profileId,
    ) {}
}
