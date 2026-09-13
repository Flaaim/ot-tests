<?php

declare(strict_types=1);

namespace App\Profile\Query\Get;

final class NetworkDTO
{
    public function __construct(
        public string $name,
        public string $identity
    ) {}

    public static function fromArray(array $network): self
    {
        return new self(
            name: $network['name'] ?? '',
            identity: $network['identity'] ?? ''
        );
    }
}
