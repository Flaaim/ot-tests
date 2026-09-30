<?php

declare(strict_types=1);

namespace App\Admin\Stats\Query;

use Doctrine\DBAL\Connection;

final class StatsFetcher implements StatsFetcherInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getUsersStats(): ?array
    {
        $sql = <<<'SQL'
              SELECT
                  COUNT(id) as total_users,
                  COUNT(id) FILTER (WHERE date >= (CURRENT_DATE - INTERVAL '30 days')) as registrations_last_30_days,
                  COUNT(id) FILTER (WHERE date >= CURRENT_DATE) as registrations_today,
                  COUNT(id) FILTER (WHERE date >= (CURRENT_DATE - INTERVAL '1 week')) as registrations_this_week
              FROM users
            SQL;

        $result = $this->connection->fetchAssociative($sql);

        if (false === $result) {
            return null;
        }
        return $result;
    }

    public function getAttemptsStats(): ?array
    {
        $sql = <<<'SQL'
                WITH stats AS (
                SELECT
                    COUNT(id) as total_attempts,
                    COUNT(id) FILTER (WHERE finished_at >= CURRENT_DATE) as attempts_today,
                    COUNT(id) FILTER (WHERE finished_at >= (CURRENT_DATE - INTERVAL '1 week')) as attempts_this_week,
                    COUNT(id) FILTER (WHERE status = 'passed') as total_passed_attempts
                FROM attempts
                )
                SELECT
                    total_attempts,
                    attempts_today,
                    attempts_this_week,
                    total_passed_attempts,
                ROUND(
                    total_passed_attempts::NUMERIC / NULLIF(total_attempts, 0) * 100,
                    1
                ) as success_rate
            FROM stats
            SQL;

        $result = $this->connection->fetchAssociative($sql);
        if (false === $result) {
            return null;
        }
        return $result;
    }
}
