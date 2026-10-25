<?php

declare(strict_types=1);

namespace App\Notification\Interfaces;

use Generator;

interface ProfileInterface
{
    public function hasProfiles(): bool;

    public function getProfileIds(): Generator;
}
