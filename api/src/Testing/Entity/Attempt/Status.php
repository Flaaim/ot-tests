<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Webmozart\Assert\Assert;

final class Status
{
    public const string STATUS_IN_PROGRESS = 'in_progress';
    public const string STATUS_FAILED = 'failed';
    public const string STATUS_PASSED = 'passed';
    public const string STATUS_CANCELLED = 'cancelled';
    public const string STATUS_TIMEOUT = 'timeout';

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [
            self::STATUS_FAILED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PASSED,
            self::STATUS_CANCELLED,
            self::STATUS_TIMEOUT,
        ]);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function inProgress(): self
    {
        return new self(self::STATUS_IN_PROGRESS);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function passed(): self
    {
        return new self(self::STATUS_PASSED);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function failed(): self
    {
        return new self(self::STATUS_FAILED);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function timeout(): self
    {
        return new self(self::STATUS_TIMEOUT);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function cancelled(): self
    {
        return new self(self::STATUS_CANCELLED);
    }
}
