<?php

declare(strict_types=1);

namespace App\Profile\Api;

use App\Notification\Interfaces\ProfileInterface;
use App\Profile\Query;

final readonly class ProfileApi implements ProfileInterface
{

    public function __construct(
        private Query\HasProfiles\QueryHandler $hasProfilesHandler,
    ) {}

    public function hasProfiles(): bool
    {
        return $this->hasProfilesHandler->handle();
    }
}
