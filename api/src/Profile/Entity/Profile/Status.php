<?php

declare(strict_types=1);

namespace App\Profile\Entity\Profile;

use Webmozart\Assert\Assert;

final class Status
{
    public const string BANNED = 'banned';
    public const string OK = 'ok';

    public function __construct(
        private readonly string $name
    ) {
        Assert::oneOf($name, [self::BANNED, self::OK]);
    }

    public static function banned(): self
    {
        return new self(self::BANNED);
    }

    public static function ok(): self
    {
        return new self(self::OK);
    }

    public function isBanned(): bool
    {
        return self::BANNED === $this->name;
    }

    public function isOk(): bool
    {
        return self::OK === $this->name;
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
