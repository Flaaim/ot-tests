<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

final class Role
{
    public const string USER = 'user';
    public const string COMPANY = 'company';
    public const string ADMIN = 'admin';

    public function __construct(
        private string $name
    ) {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
            self::COMPANY,
        ]);
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function teacher(): self
    {
        return new self(self::COMPANY);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->name === $other->name;
    }

    public function isAdmin(): bool
    {
        return self::ADMIN === $this->name;
    }
}
