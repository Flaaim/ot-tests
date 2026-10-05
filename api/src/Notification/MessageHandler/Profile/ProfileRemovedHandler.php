<?php

declare(strict_types=1);

namespace App\Notification\MessageHandler\Profile;

use App\Notification\Entity\Message\MessageRepository;
use App\Profile\Event\ProfileRemoved;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ProfileRemovedHandler
{
    public function __construct(
        private MessageRepository $messages
    ) {}

    public function __invoke(ProfileRemoved $event): void
    {
        $profileId = $event->id;
        $this->messages->removeByProfile($profileId);
    }
}
