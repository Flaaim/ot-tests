<?php

declare(strict_types=1);

namespace App\Course\Query\Course\GetQuestions;

final class AnswerDTO
{
    public function __construct(
        public string $id,
        public string $text,
        public string $answerImg,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            text: $data['text'],
            answerImg: $data['answerImg'] ?? '',
        );
    }
}
