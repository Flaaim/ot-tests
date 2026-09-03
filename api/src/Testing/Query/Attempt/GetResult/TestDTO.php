<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

final class TestDTO
{
    public function __construct(
        public string $name,
        public string $cipher,
        public int $allowedMistakes
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            cipher: $data['cipher'],
            allowedMistakes: $data['allowed_mistakes'],
        );
    }
}
