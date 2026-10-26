<?php

declare(strict_types=1);

namespace App\Auth\Service\OAuth;

use App\Auth\Command\JoinByNetwork\Command;
use App\Auth\Command\JoinByNetwork\Handler;
use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\OAuth\Interfaces\SocialAuthInterface;
use DomainException;

/** @psalm-suppress UnusedClass */
final readonly class SocialAuthenticator implements SocialAuthInterface
{
    public function __construct(
        private UserRepository $users,
        private Handler $joinHandler
    ) {}

    public function authenticate(string $network, string $identity, string $email): string
    {
        $user = $this->users->findByNetwork($network, $identity);
        if ($user) {
            return $user->getId()->getValue();
        }
        $existingEmailUser = $this->users->findByEmail(new Email($email));
        if ($existingEmailUser) {
            throw new DomainException('Пользователь с таким email уже существует. Войдите обычным способом и привяжите аккаунт в настройках профиля.');
        }

        $this->joinHandler->handle(new Command($email, $network, $identity));
        $newUser = $this->users->findByNetwork($network, $identity);
        if (null === $newUser) {
            throw new DomainException('Ошибка при создании пользователя.');
        }
        return $newUser->getId()->getValue();
    }
}
