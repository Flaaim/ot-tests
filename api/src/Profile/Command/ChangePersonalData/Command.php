<?php

declare(strict_types=1);

namespace App\Profile\Command\ChangePersonalData;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 55)]
        public string $name,
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 75)]
        public string $surname
    ) {}
}
