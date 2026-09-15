<?php

declare(strict_types=1);

namespace App\Auth\MessageHandler\Profile;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\CredentialsSender;
use App\Auth\Service\PasswordGenerator;
use App\Auth\Service\PasswordHasher;
use App\Infrastructure\Doctrine\Flusher;
use App\Profile\Event\ProfileAdded;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** @psalm-suppress UnusedClass */
#[AsMessageHandler]
final readonly class ProfileAddedHandler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $hasher,
        private PasswordGenerator $passwordGenerator,
        private CredentialsSender $credentialsSender,
        private Flusher $flusher,
    ) {}

    public function __invoke(ProfileAdded $event): void
    {
        $email = new Email($event->email);
        $role = new Role($event->role);
        if ($this->users->hasByEmail($email)) {
            return;
        }

        $password = $this->passwordGenerator->generate();

        $user = User::joinByAdmin(
            id: new Id($event->id),
            date: new DateTimeImmutable(),
            email: $email,
            role: $role,
            passwordHash: $this->hasher->hash($password),
        );

        $this->users->add($user);

        $this->flusher->flush();

        $this->credentialsSender->send($email, $password);
    }
}
