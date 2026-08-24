<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
final class AnswerIdType extends StringType
{
    public const string NAME = 'attempt_answer_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AnswerId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AnswerId
    {
        return !empty($value) ? new AnswerId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
