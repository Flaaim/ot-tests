<?php

declare(strict_types=1);

namespace App\Testing\MessageHandler\Attempt;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;
use App\Testing\Event\Attempt\TimeoutAttemptCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** @psalm-suppress UnusedClass */
#[AsMessageHandler]
final class TimeoutAttemptHandler
{
    public function __construct(
        private readonly AttemptRepository $attempts,
        private readonly Flusher $flusher
    ) {}

    public function __invoke(TimeoutAttemptCommand $command): void
    {
        $attempt = $this->attempts->get(new AttemptId($command->attemptId));

        if (!$attempt->isInProgress()) {
            return;
        }

        if ($attempt->getStartedAt()->getTimestamp() !== $command->startedAt->getTimestamp()) {
            return;
        }

        $attempt->finishByTimeout();

        $this->flusher->flush();
    }
}
