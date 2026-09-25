<?php

declare(strict_types=1);

namespace App\TgNotifier\Command\ParseChats;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Yaml]
        public string $text
    ) {}
}
