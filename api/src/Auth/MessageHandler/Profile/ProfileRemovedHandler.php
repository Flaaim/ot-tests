<?php

declare(strict_types=1);

namespace App\Auth\MessageHandler\Profile;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Infrastructure\Doctrine\Flusher;
use App\Profile\Event\ProfileRemoved;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** @psalm-suppress UnusedClass */
#[AsMessageHandler]
final readonly class ProfileRemovedHandler
{
    public function __construct(
        private UserRepository $users,
        private Flusher $flusher,
    ) {}

    public function __invoke(ProfileRemoved $event): void
    {
        $user = $this->users->findById(new Id($event->id));

        if (null === $user) {
            return;
        }

        $user->remove();

        $this->users->remove($user);

        $this->flusher->flush();
    }
}
