<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\Finish;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;
use App\Testing\Entity\Test\TestId;
use App\Testing\Entity\Test\TestRepository;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod  */
    public function __construct(
        private readonly AttemptRepository $attempts,
        private readonly TestRepository $tests,
        private readonly Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $attempt = $this->attempts->get(new AttemptId($command->attemptId));

        $test = $this->tests->get(new TestId($attempt->getTestId()));

        $allowedMistakes = $test->getSettings()->getAllowedMistakes();

        $attempt->finish($allowedMistakes);

        $this->flusher->flush();
    }
}
