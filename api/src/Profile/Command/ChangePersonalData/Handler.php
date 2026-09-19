<?php

declare(strict_types=1);

namespace App\Profile\Command\ChangePersonalData;

use App\Infrastructure\Doctrine\Flusher;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Entity\Profile\ProfileRepository;

final readonly class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ProfileRepository $profiles,
        private Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $profile = $this->profiles->get(new ProfileId($command->id));

        $profile->changePersonalData($command->name, $command->surname);

        $this->flusher->flush();
    }
}
