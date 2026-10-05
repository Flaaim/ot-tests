<?php

declare(strict_types=1);

namespace App\Notification\Event;

final class NotificationCreated
{
    public function __construct(
        public string $notificationId,
    ) {}
}
