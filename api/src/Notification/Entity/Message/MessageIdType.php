<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class MessageIdType extends StringType
{
    public const string NAME = 'ntf_message_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof MessageId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MessageId
    {
        return !empty($value) ? new MessageId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
