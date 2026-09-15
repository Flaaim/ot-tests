<?php

declare(strict_types=1);

namespace App\Profile\Entity\Profile;

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

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function company(): self
    {
        return new self(self::COMPANY);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function isEqualTo(self $other): bool
    {
        return $this->name === $other->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function isAdmin(): bool
    {
        return self::ADMIN === $this->name;
    }
}
