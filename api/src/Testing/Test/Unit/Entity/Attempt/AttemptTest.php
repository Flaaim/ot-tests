<?php

declare(strict_types=1);

namespace App\Testing\Test\Unit\Entity\Attempt;

use App\Testing\Entity\Attempt\Attempt;
use App\Testing\Entity\Attempt\AttemptId;
use App\Testing\Entity\Attempt\Status;
use PHPUnit\Framework\TestCase;

final class AttemptTest extends TestCase
{
    public function testAttempt(): void
    {
        $attempt = new Attempt(
            $id = AttemptId::generate(),
            $testId = '244b276c-0f55-4940-8d73-e87f9aacab42',
            $userId = '6b3ae503-2e33-45bf-be3d-93c7383aebf9',
            $status = Status::inProgress(),
            $startedAt = new \DateTimeImmutable(),
            $ticketNumber = 1,
            $questionIds = ['bf666e2f-7bab-4a31-9f2f-77735cebbcb0', '0d13332b-84de-4a8e-96fc-7b8ea779f0b7', '958ab569-243e-418e-8355-38daec6eb8b3'],
        );

        self::assertEquals($id->getValue(), $attempt->getId()->getValue());
        self::assertEquals($testId, $attempt->getTestId());
        self::assertEquals($userId, $attempt->getUserId());
        self::assertEquals($status, $attempt->getStatus());
        self::assertEquals($startedAt, $attempt->getStartedAt());
        self::assertEquals($ticketNumber, $attempt->getTicketNumber());
        self::assertEquals($questionIds, $attempt->getQuestionIds());

    }
}
