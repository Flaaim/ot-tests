<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByMagicLink\Confirm;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Event\GuestJoined;
use App\Infrastructure\Doctrine\Flusher;
use DateTimeImmutable;
use DomainException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private Flusher $flusher,
        private MessageBusInterface $messageBus,
    ) {}

    public function handle(Command $command): User
    {
        $user = $this->users->findByMagicLinkToken($command->token);
        if (null === $user) {
            throw new DomainException('Incorrect magicLinkToken.');
        }

        if (!$user->getEmail()->isEqualTo(new Email($command->email))) {
            throw new DomainException('Incorrect magicLinkEmail.');
        }

        $user->confirmMagicLink($command->token, new DateTimeImmutable());

        $this->flusher->flush();

        if (null !== $command->guestId) {
            $this->messageBus->dispatch(
                new GuestJoined(
                    $user->getId()->getValue(),
                    $command->guestId
                )
            );
        }

        return $user;
    }
}
