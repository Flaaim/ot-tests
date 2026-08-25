<?php

declare(strict_types=1);

namespace App\Course\Query\Course\GetQuestions;

final class QuestionDTO
{
    public function __construct(
        public string $id,
        public string $text,
        public string $questionImg,
        public array $answers,
        public string $form,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            text: $data['text'],
            questionImg: $data['question_img'] ?? '',
            answers: $data['answers'] ?? [],
            form: $data['form'],
        );
    }
}
