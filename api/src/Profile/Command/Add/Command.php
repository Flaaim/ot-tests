<?php

declare(strict_types=1);

namespace App\Profile\Command\Add;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 15)]
        public string $password,
        public ?string $name = null,
        public ?string $surname = null,
    ) {}
}
