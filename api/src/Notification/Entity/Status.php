<?php

declare(strict_types=1);

namespace App\Notification\Entity;

use Webmozart\Assert\Assert;

final class Status
{
    public const string COMPLETED = 'completed';
    public const string IN_PROGRESS = 'in_progress';
    public const string FAILED = 'failed';

    public function __construct(
        private readonly string $name
    ) {
        Assert::oneOf($name, [self::COMPLETED, self::IN_PROGRESS, self::FAILED]);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function inProgress(): self
    {
        return new self(self::IN_PROGRESS);
    }

    public static function failed(): self
    {
        return new self(self::FAILED);
    }

    public function isCompleted(): bool
    {
        return self::COMPLETED === $this->name;
    }

    public function isInProgress(): bool
    {
        return self::IN_PROGRESS === $this->name;
    }

    public function isFailed(): bool
    {
        return self::FAILED === $this->name;
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
