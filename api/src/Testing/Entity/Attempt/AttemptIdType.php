<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class AttemptIdType extends StringType
{
    public const string NAME = 'attempt_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AttemptId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AttemptId
    {
        return !empty($value) ? new AttemptId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
