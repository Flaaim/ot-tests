<?php

declare(strict_types=1);

namespace App\TgNotifier\Query\Contact;

interface ContactFetcherInterface
{
    public function getAll(): array;
}
