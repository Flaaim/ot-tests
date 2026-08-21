<?php

declare(strict_types=1);

namespace App\Testing\Command\Attempt\Launch;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\AttemptRepository;
use App\Testing\Entity\Attempt\Status;
use App\Testing\Entity\Test\TestId;
use App\Testing\Entity\Test\TestRepository;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly TestRepository $tests,
        private readonly AttemptRepository $attempts,
        private readonly Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $test = $this->tests->get(new TestId($command->testId));

        $tickets = $test->getTickets();
        $ticket = $tickets[$command->ticketNumber] ?? null;

        if (null === $ticket) {
            throw new DomainException('Invalid ticket number.');
        }

        $attempt = new Attempt(
            AttemptId::generate(),
            $test->getId()->getValue(),
            $command->userId,
            Status::inProgress(),
            new DateTimeImmutable(),
            $ticket->number,
            $ticket->questionIds
        );

        $this->attempts->add($attempt);

        $this->flusher->flush();
    }
}
