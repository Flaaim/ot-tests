<?php

declare(strict_types=1);

namespace App\Testing\Test\Unit\Entity\Attempt;

use App\Testing\Entity\Attempt\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class StatusTest extends TestCase
{
    public function testInvalid(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Status('invalid');
    }

    public function testInProgress(): void
    {
        $status = new Status(Status::STATUS_IN_PROGRESS);
        self::assertEquals(Status::STATUS_IN_PROGRESS, $status->getValue());
    }

    public function testFailed(): void
    {
        $status = new Status(Status::STATUS_FAILED);
        self::assertEquals(Status::STATUS_FAILED, $status->getValue());
    }

    public function testPassed(): void
    {
        $status = new Status(Status::STATUS_PASSED);
        self::assertEquals(Status::STATUS_PASSED, $status->getValue());
    }

    public function testCancelled(): void
    {
        $status = new Status(Status::STATUS_CANCELLED);
        self::assertEquals(Status::STATUS_CANCELLED, $status->getValue());
    }

    public function testTimeout(): void
    {
        $status = new Status(Status::STATUS_TIMEOUT);
        self::assertEquals(Status::STATUS_TIMEOUT, $status->getValue());
    }
}
