<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\SubmitAnswer;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly AttemptRepository $attempts,
        private readonly Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $attempt = $this->attempts->get(new AttemptId($command->attemptId));

        $attempt->submitAnswer(
            $command->questionId,
            $command->selectedAnswersIds
        );

        $this->flusher->flush();
    }
}
