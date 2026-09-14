<?php

declare(strict_types=1);

namespace App\Auth\MessageHandler\Profile;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
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
        private Flusher $flusher,
    ) {}

    public function __invoke(ProfileAdded $event): void
    {
        $email = new Email($event->email);
        if ($this->users->hasByEmail($email)) {
            return;
        }

        $user = User::joinByAdmin(
            id: new Id($event->id),
            date: new DateTimeImmutable(),
            email: $email,
            passwordHash: $this->hasher->hash($event->password),
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}
