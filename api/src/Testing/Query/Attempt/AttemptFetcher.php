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

    public function getQuestionIds(string $attemptId): array
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

        return $questionIds;
    }
}
