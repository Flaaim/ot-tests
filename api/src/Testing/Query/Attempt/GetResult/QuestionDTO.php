<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt\GetResult;

final class QuestionDTO
{
    public function __construct(
        public string $id,
        public string $text,
        public ?string $questionImg,
        public array $answers,
        public string $form,
        public ?UserResultDTO $userResult
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            text: $data['text'],
            questionImg: $data['question_img'] ?? null,
            answers: $data['answers'],
            form: $data['form'],
            userResult: isset($data['user_result']) ? UserResultDTO::fromArray($data['user_result']) : null
        );
    }
}
