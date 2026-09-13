<?php

declare(strict_types=1);

namespace App\Profile\Event;

final class ProfileRemoved
{
    public function __construct(
        public string $id
    ) {}
}
