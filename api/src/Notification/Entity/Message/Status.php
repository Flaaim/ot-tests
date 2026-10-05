<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

enum Status: string
{
    case NOT_READ = 'not_read';
    case READ = 'read';
}
