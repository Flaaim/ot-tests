<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\SubmitAnswer;

use App\Course\Api\Course\CheckAnswer\QueryHandlerApi;
use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\Answer;
use App\Testing\Entity\Attempt\AnswerId;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly AttemptRepository $attempts,
        private readonly QueryHandlerApi $queryHandler,
        private readonly Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $attempt = $this->attempts->get(new AttemptId($command->attemptId));

        $isCorrect = $this->queryHandler->handle($command->questionId, $command->selectedAnswersIds);

        $answer = new Answer(
            AnswerId::generate(),
            $command->questionId,
            $command->selectedAnswersIds,
            $isCorrect
        );

        $attempt->submitAnswer($answer);

        $this->flusher->flush();
    }
}
