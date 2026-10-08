<?php

declare(strict_types=1);

namespace App\Notification\MessageHandler\User;

use App\Auth\Event\UserCreated;
use App\Notification\Command\Notification\SendPersonal\Command;
use App\Notification\Service\Templates\WelcomeTemplate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class UserCreatedHandler
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {}

    public function __invoke(UserCreated $event): void
    {
        $profileId = $event->id;
        $subject = WelcomeTemplate::getSubject();
        $message = WelcomeTemplate::getMessage($event->email);

        $command = new Command(
            $profileId,
            $subject,
            $message,
        );

        $this->commandBus->dispatch($command);
    }
}
