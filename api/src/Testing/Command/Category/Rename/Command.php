<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Rename;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $id,
        #[Assert\NotBlank]
        public readonly string $name
    ) {}
}
