<?php

declare(strict_types=1);

namespace App\TgNotifier\Entity\Contact;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class ContactIdType extends StringType
{
    public const string NAME = 'contact_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ContactId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ContactId
    {
        return !empty($value) ? new ContactId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
