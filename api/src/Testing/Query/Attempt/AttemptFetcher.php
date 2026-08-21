<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;

final class AttemptFetcher implements AttemptFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function getQuestions(string $attemptId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $attempt = $qb->select('a.question_ids')
            ->from('attempts', 'a')
            ->where($qb->expr()->eq('a.id', ':id'))
            ->setParameter('id', $attemptId)
            ->executeQuery()
            ->fetchAssociative();

        if (false === $attempt || empty($attempt['question_ids'])) {
            return [];
        }

        $questionIds = json_decode($attempt['question_ids'], true, 512, JSON_THROW_ON_ERROR);

        if (empty($questionIds)) {
            return [];
        }

        $qbQuestions = $this->connection->createQueryBuilder();
        $result = $qbQuestions->select('q.id, q.text, q.question_img, q.answers, q.form')
            ->from('questions', 'q')
            ->where($qbQuestions->expr()->in('q.id', ':questionIds'))
            ->setParameter('questionIds', $questionIds, ArrayParameterType::STRING)
            ->executeQuery();

        $questions = $result->fetchAllAssociative();

        if (empty($questions)) {
            return [];
        }
        $data = [];
        foreach ($questions as $question) {
            $answers = json_decode($question['answers'], true, 512, JSON_THROW_ON_ERROR);

            $safeAnswers = array_map(static function (array $answer) {
                unset($answer['isCorrect']);
                return $answer;
            }, $answers);

            $data[] = [
                'id' => $question['id'],
                'text' => $question['text'],
                'question_img' => $question['question_img'],
                'answers' => $safeAnswers,
                'form' => $question['form'],
            ];
        }

        return $data;
    }
}
