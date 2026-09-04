<?php

declare(strict_types=1);

namespace App\Testing\Test\Unit\Entity\Attempt;

use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\Status;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AttemptTest extends TestCase
{
    public function testAttempt(): void
    {
        $attempt = new Attempt(
            $id = AttemptId::generate(),
            $testId = '244b276c-0f55-4940-8d73-e87f9aacab42',
            $userId = '6b3ae503-2e33-45bf-be3d-93c7383aebf9',
            $status = Status::inProgress(),
            $startedAt = new DateTimeImmutable(),
            $ticketNumber = 1,
            $questionsSnapshot = [],
        );

        self::assertEquals($id->getValue(), $attempt->getId()->getValue());
        self::assertEquals($testId, $attempt->getTestId());
        self::assertEquals($userId, $attempt->getUserId());
        self::assertEquals($status, $attempt->getStatus());
        self::assertEquals($startedAt, $attempt->getStartedAt());
        self::assertEquals($ticketNumber, $attempt->getTicketNumber());
        self::assertEquals($questionsSnapshot, $attempt->getQuestionSnapshot());
    }

    public function testFinishFailed(): void
    {
        $attempt = new Attempt(
            AttemptId::generate(),
            '244b276c-0f55-4940-8d73-e87f9aacab42',
            '6b3ae503-2e33-45bf-be3d-93c7383aebf9',
            Status::inProgress(),
            new DateTimeImmutable(),
            1,
            [],
            0,
            8
        );

        $attempt->finish(2);

        self::assertNotNull($attempt->getFinishedAt());
        self::assertEquals(Status::failed(), $attempt->getStatus());
    }

    public function testFinishPassed(): void
    {
        $attempt = new Attempt(
            AttemptId::generate(),
            '244b276c-0f55-4940-8d73-e87f9aacab42',
            '6b3ae503-2e33-45bf-be3d-93c7383aebf9',
            Status::inProgress(),
            new DateTimeImmutable(),
            1,
            [],
            8,
            1
        );

        $attempt->finish(2);
        self::assertNotNull($attempt->getFinishedAt());
        self::assertEquals(Status::passed(), $attempt->getStatus());
    }

    public function testFinishAlready(): void
    {
        $attempt = new Attempt(
            AttemptId::generate(),
            '244b276c-0f55-4940-8d73-e87f9aacab42',
            '6b3ae503-2e33-45bf-be3d-93c7383aebf9',
            Status::passed(),
            new DateTimeImmutable(),
            1,
            [],
            8,
            1
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage('Attempt is already finished.');
        $attempt->finish(2);
    }
}
