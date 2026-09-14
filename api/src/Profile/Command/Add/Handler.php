<?php

declare(strict_types=1);

namespace App\Profile\Command\Add;

use App\Infrastructure\Doctrine\Flusher;
use App\Profile\Entity\Profile\Email;
use App\Profile\Entity\Profile\Profile;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Entity\Profile\ProfileRepository;
use App\Profile\Entity\Profile\Role;
use App\Profile\Entity\Profile\Status;
use App\Profile\Event\ProfileAdded;
use DomainException;
use Symfony\Component\Messenger\MessageBusInterface;

/** @psalm-suppress UnusedClass */
final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly ProfileRepository $profiles,
        private readonly Flusher $flusher,
        private readonly MessageBusInterface $messageBus,
    ) {}

    public function handle(Command $command): void
    {
        $email = new Email($command->email);
        if ($this->profiles->hasByEmail($email)) {
            throw new DomainException('This email already exists.');
        }

        $profile = new Profile(
            $profileId = ProfileId::generate(),
            $email,
            Role::user(),
            Status::ok(),
            $command->name,
            $command->surname
        );

        $this->profiles->add($profile);

        $this->flusher->flush();

        $this->messageBus->dispatch(new ProfileAdded(
            id: $profileId->getValue(),
            email: $email->getValue(),
            password: $command->password,
        ));
    }
}
