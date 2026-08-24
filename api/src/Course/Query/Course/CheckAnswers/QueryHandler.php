<?php

declare(strict_types=1);

namespace App\Course\Query\Course\CheckAnswers;

use App\Course\Query\Course\CourseFetcherInterface;
use App\Course\Service\AnswerChecker;

final class QueryHandler
{
    public function __construct(
        private readonly CourseFetcherInterface $courses,
        private readonly AnswerChecker $checker,
    ) {}

    public function handle(Query $query): bool
    {
        $answers = $this->courses->getQuestionAnswers($query->questionId);

        return $this->checker->check($answers, $query->selectedAnswersIds);
    }
}
