<?php

declare(strict_types=1);

namespace App\Notification\Query\Notification;

interface NotificationFetcherInterface
{
    public function getPaginated(int $page, int $limit = 15): array;
}
