<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class PasswordGeneratorTest extends TestCase
{
    public function testPasswordLengthIsWithinBounds(): void
    {
        $generator = new PasswordGenerator();
        for ($i = 0; $i < 100; ++$i) {
            $password = $generator->generate();
            $length = \strlen($password);

            self::assertGreaterThanOrEqual(6, $length, "Пароль слишком короткий: {$password}");
            self::assertLessThanOrEqual(15, $length, "Пароль слишком длинный: {$password}");
        }
    }

    public function testPasswordContainsOnlyValidCharacters(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generate();

        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $password);
    }

    public function testPasswordIsNotEmptyString(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generate();

        self::assertNotEmpty($password);
    }

    public function testConsecutivePasswordsAreNotTheSame(): void
    {
        $generator = new PasswordGenerator();
        $password1 = $generator->generate();
        $password2 = $generator->generate();

        self::assertNotEquals($password1, $password2, 'Функция сгенерировала два одинаковых пароля подряд');
    }
}
