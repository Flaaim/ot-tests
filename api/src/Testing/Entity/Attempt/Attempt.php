<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attempts')]
final class Attempt implements AggregateRoot
{
    use EventTrait;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'attempt_id', unique: true)]
        private AttemptId $id,
        #[ORM\Column(type: 'string')]
        private string $testId,
        #[ORM\Column(type: 'string')]
        private string $userId,
        #[ORM\Column(type: 'attempt_status')]
        private Status $status,
        #[ORM\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $startedAt,
        #[ORM\Column(type: 'integer', nullable: true)]
        private ?int $ticketNumber,
        #[ORM\Column(type: Types::JSON, options: ['jsonb' => true])]
        private array $questionIds,
        #[ORM\Column(type: 'integer')]
        private int $score = 0,
        #[ORM\Column(type: 'integer')]
        private int $mistakes = 0,
        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
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
