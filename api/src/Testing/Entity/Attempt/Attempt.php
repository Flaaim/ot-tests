<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;

final class Attempt implements AggregateRoot
{
    use EventTrait;

    public function __construct(
        private AttemptId $id,
        private string $testId,
        private string $userId,
        private Status $status,
        private DateTimeImmutable $startedAt,
        private ?int $ticketNumber,
        private array $questionIds,
        private int $score = 0,
        private int $mistakes = 0,
        private ?DateTimeImmutable $finishedAt = null,
    ) {}

    public function getId(): AttemptId
    {
        return $this->id;
    }

    public function getTestId(): string
    {
        return $this->testId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getTicketNumber(): ?int
    {
        return $this->ticketNumber;
    }

    public function getQuestionIds(): array
    {
        return $this->questionIds;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getMistakes(): int
    {
        return $this->mistakes;
    }

    public function getFinishedAt(): DateTimeImmutable
    {
        return $this->finishedAt;
    }
}
