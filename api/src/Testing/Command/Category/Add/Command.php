<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Add;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $description,
        #[Assert\Uuid]
        public ?string $parentId,
    ) {}
}
