<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordGenerator;
use PHPUnit\Framework\TestCase;

final class PasswordGeneratorTest extends TestCase
{
    public function testPasswordLengthIsWithinBounds(): void
    {
        $generator = new PasswordGenerator();
        for ($i = 0; $i < 100; $i++) {
            $password = $generator->generate();
            $length = strlen($password);

            $this->assertGreaterThanOrEqual(6, $length, "Пароль слишком короткий: $password");
            $this->assertLessThanOrEqual(15, $length, "Пароль слишком длинный: $password");
        }
    }

    public function testPasswordContainsOnlyValidCharacters(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generate();

        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $password);
    }

    public function testPasswordIsNotEmptyString(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generate();

        $this->assertIsString($password);
        $this->assertNotEmpty($password);
    }

    public function testConsecutivePasswordsAreNotTheSame(): void
    {
        $generator = new PasswordGenerator();
        $password1 = $generator->generate();
        $password2 = $generator->generate();

        $this->assertNotEquals($password1, $password2, 'Функция сгенерировала два одинаковых пароля подряд');
    }
}
