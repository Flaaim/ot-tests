<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'answers')]
final class Answer
{
    #[ORM\ManyToOne(targetEntity: Attempt::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(name: 'attempt_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Attempt $attempt;

    public function __construct(
        private AnswerId $id,
        #[ORM\Column(type: 'string')]
        private string $questionId,
        #[ORM\Column(type: Types::JSON, options: ['jsonb' => true])]
        private array $selectedAnswersIds,
        #[ORM\Column(type: 'boolean')]
        private bool $isCorrect,
    ) {}

    public function getId(): AnswerId
    {
        return $this->id;
    }

    public function getAttempt(): Attempt
    {
        return $this->attempt;
    }

    public function getQuestionId(): string
    {
        return $this->questionId;
    }

    public function getSelectedAnswersIds(): array
    {
        return $this->selectedAnswersIds;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function appendAttempt(Attempt $attempt): void
    {
        $this->attempt = $attempt;
    }
}
