<?php

declare(strict_types=1);

namespace App\Admin\Stats\Query\PopularTests;

final readonly class PopularTestsDTO
{
    public function __construct(
        public string $name,
        public string $cipher,
        public int $totalAttempts,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            cipher: $data['cipher'],
            totalAttempts: $data['total_attempts'],
        );
    }
}
