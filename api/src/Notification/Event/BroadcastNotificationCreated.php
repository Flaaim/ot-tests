<?php

declare(strict_types=1);

namespace App\Notification\Event;

final class BroadcastNotificationCreated
{
    public function __construct(
        public string $notificationId,
    ) {}
}
