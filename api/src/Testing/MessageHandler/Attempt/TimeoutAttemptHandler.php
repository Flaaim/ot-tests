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

    public function __invoke(TimeoutAttemptCommand $event): void
    {
        $attempt = $this->attempts->get(new AttemptId($event->attemptId));

        if (!$attempt->isInProgress()) {
            return;
        }

        $attempt->finishByTimeout();

        $this->flusher->flush();
    }
}
