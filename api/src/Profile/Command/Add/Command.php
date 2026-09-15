<?php

declare(strict_types=1);

namespace App\Profile\Command\Add;

use App\Profile\Entity\Profile\Role;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\Choice(choices: [Role::USER, Role::COMPANY])]
        public string $role,
        public ?string $name = null,
        public ?string $surname = null,
    ) {}
}
