<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

enum Type: string
{
    case SYSTEM = 'system';
    case ADMIN = 'admin';
}
