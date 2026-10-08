<?php

declare(strict_types=1);

namespace App\Notification\Service\Templates;

final class WelcomeTemplate
{
    public static function getSubject(): string
    {
        return 'Добро пожаловать на платформу!';
    }

    public static function getMessage(string $email): string
    {
        return \sprintf(
            "Здравствуйте, **%s**!\n\nВаша учетная запись успешно создана. Теперь вам доступны все тесты в разделе [Каталог](/catalog). Если вам необходимо подготовиться к аттестации в Ростехндазоре, то используйте для этого сайт [rtn-tests.ru](https://rtn-tests.ru)",
            $email
        );
    }
}
