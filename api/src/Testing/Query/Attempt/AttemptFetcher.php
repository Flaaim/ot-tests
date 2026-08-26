<?php

declare(strict_types=1);

namespace App\Testing\Query\Attempt;

use Doctrine\DBAL\Connection;

/** @psalm-suppress UnusedClass */
final class AttemptFetcher implements AttemptFetcherInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function getOneById(string $attemptId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $attempt = $qb->select('a.id, a.status, a.ticket_number, a.questions_snapshot')
            ->from('attempts', 'a')
            ->where($qb->expr()->eq('a.id', ':id'))
            ->setParameter('id', $attemptId)
            ->executeQuery()
            ->fetchAssociative();

        if (false === $attempt || empty($attempt['questions_snapshot'])) {
            return [];
        }

        $questionsSnapshot = json_decode($attempt['questions_snapshot'], true, 512, JSON_THROW_ON_ERROR);

        if (empty($questionsSnapshot)) {
            return [];
        }

        foreach ($questionsSnapshot as &$question) {
            if (isset($question['form']) && 'matching' === $question['form']) {
                continue;
            }

            $safeAnswers = array_map(static function (array $answer) {
                unset($answer['isCorrect']);
                return $answer;
            }, $question['answers']);

            $question['answers'] = $safeAnswers;
        }
        unset($question);

        return [
            'id' => $attempt['id'],
            'status' => $attempt['status'],
            'ticket_number' => $attempt['ticket_number'],
            'questions_snapshot' => $questionsSnapshot,
        ];
    }

    public function getAttemptResult(string $attemptId): array {}
}
