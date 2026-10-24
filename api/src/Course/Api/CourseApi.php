<?php

declare(strict_types=1);

namespace App\Course\Api;

use App\Course\Query\Course;
use App\Testing\Interfaces\CourseInterface;

final readonly class CourseApi implements CourseInterface
{
    public function __construct(
        private Course\GetQuestionsIds\QueryHandler $getQuestionsIdsHandler,
        private Course\GetQuestions\QueryHandler $getQuestionsQueryHandler,
    ) {}

    public function getQuestionIds(string $courseId): array
    {
        return $this->getQuestionsIdsHandler->handle(new Course\GetQuestionsIds\Query($courseId));
    }

    public function getQuestions(array $questionIds): array
    {
        return $this->getQuestionsQueryHandler->handle(new Course\GetQuestions\Query($questionIds));
    }
}
