<?php

declare(strict_types=1);

namespace App\Testing\Test\Unit\Entity\Attempt;

use App\Testing\Entity\Attempt\Answer;
use App\Testing\Entity\Attempt\AnswerId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AnswerTest extends TestCase
{
    public function testAnswer(): void
    {
        $attemptAnswer = new Answer(
            $id = AnswerId::generate(),
            $questionId = Uuid::uuid4()->toString(),
            $selectedAnswerIds = ['c6e57c9a-6b86-41f6-83cd-6379e2b9e2dd', '788a5a2f-a2a2-48d2-88c4-48dfa80ce708'],
            true
        );

        self::assertEquals($id, $attemptAnswer->getId());
        self::assertEquals($questionId, $attemptAnswer->getQuestionId());
        self::assertEquals($selectedAnswerIds, $attemptAnswer->getSelectedAnswersIds());
        self::assertTrue($attemptAnswer->isCorrect());
    }
}
