<?php

declare(strict_types=1);

namespace App\Notification\Event;

final readonly class PersonalNotificationCreated
{
    public function __construct(
        public string $notificationId,
        public string $profileId
    ) {}
}
