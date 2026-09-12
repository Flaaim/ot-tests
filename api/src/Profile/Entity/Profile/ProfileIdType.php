<?php

declare(strict_types=1);

namespace App\Profile\Entity\Profile;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class ProfileIdType extends StringType
{
    public const string NAME = 'profile_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ProfileId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProfileId
    {
        return !empty($value) ? new ProfileId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
