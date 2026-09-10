<?php

declare(strict_types=1);

namespace App\Auth\Query;

interface UserFetcherInterface
{
    public function getProfile(string $id): array;

    public function getUsers(): array;
}
