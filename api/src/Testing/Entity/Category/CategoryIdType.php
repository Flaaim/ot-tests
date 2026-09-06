<?php

declare(strict_types=1);

namespace App\Testing\Entity\Category;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class CategoryIdType extends StringType
{
    public const string NAME = 'category_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CategoryId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CategoryId
    {
        return !empty($value) ? new CategoryId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
