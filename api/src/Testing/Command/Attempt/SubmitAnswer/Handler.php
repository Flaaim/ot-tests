<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\SubmitAnswer;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\Answer;
use App\Testing\Entity\Attempt\AttemptAnswerId;
use App\Testing\Entity\Attempt\AttemptRepository;

final class Handler
{
    public function __construct(
        private readonly AttemptRepository $attempts,
        private readonly Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $attemptAnswer = new Answer(
            AttemptAnswerId::generate(),
            $command->attemptId,
            $command->testId,
            $command->selectedAnswersIds
        );

        $this->attempts->addAnswer($attemptAnswer);

        $this->flusher->flush();
    }
}
