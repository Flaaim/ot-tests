<?php

declare(strict_types=1);

namespace App\Course\Service;

use App\Course\Entity\Course\QuestionForm;

final class AnswerChecker
{
    public function check(array $answerData, array $selectedAnswerIds): bool
    {
        if (empty($selectedAnswerIds)) {
            return false;
        }

        $selectedAnswerIds = array_values($selectedAnswerIds);

        if (QuestionForm::SINGLE_CHOICE === $answerData['form']) {
            if (\count($selectedAnswerIds) > 1) {
                return false;
            }
            foreach ($answerData['answers'] as $answer) {
                if ($answer['id'] === $selectedAnswerIds[0] && true === $answer['isCorrect']) {
                    return true;
                }
            }
            return false;
        }

        if (QuestionForm::MULTIPLE_CHOICE === $answerData['form']) {
            $totalCorrectAnswers = 0;
            $selectedCorrectAnswers = 0;

            foreach ($answerData['answers'] as $answer) {
                if (true === $answer['isCorrect']) {
                    ++$totalCorrectAnswers;
                }

                if (\in_array($answer['id'], $selectedAnswerIds, true) && true === $answer['isCorrect']) {
                    ++$selectedCorrectAnswers;
                }
            }

            return $selectedCorrectAnswers === $totalCorrectAnswers && \count($selectedAnswerIds) === $totalCorrectAnswers;
        }

        if (QuestionForm::SEQUENCE === $answerData['form'] || QuestionForm::MATCHING === $answerData['form']) {
            if (array_column($answerData['answers'], 'id') === $selectedAnswerIds) {
                return true;
            }
            return false;
        }

        return false;
    }
}
