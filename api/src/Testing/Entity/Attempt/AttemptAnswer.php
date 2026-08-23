<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use App\SharedDomain\Event\EventTrait;

final class AttemptAnswer
{

    public function __construct(
        private string $id,
        private string $attemptId,
        private string $questionId,
        private array $selectedAnswersIds,
        private bool $isCorrect = false,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getAttemptId(): string
    {
        return $this->attemptId;
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
}
