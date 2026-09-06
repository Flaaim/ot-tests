<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Move;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $parentId,
    ) {}
}
