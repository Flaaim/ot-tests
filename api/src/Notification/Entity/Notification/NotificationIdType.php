<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class NotificationIdType extends StringType
{
    public const string NAME = 'notification_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof NotificationId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?NotificationId
    {
        return !empty($value) ? new NotificationId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
