<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity\Notification;

use App\Notification\Entity\Notification\Status;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class StatusTest extends TestCase
{
    public function testCompleted(): void
    {
        $status = Status::completed();

        self::assertTrue($status->isCompleted());
    }

    public function testInProgress(): void
    {
        $status = Status::inProgress();
        self::assertTrue($status->isInProgress());
    }

    public function testFailed(): void
    {
        $status = Status::failed();

        self::assertTrue($status->isFailed());
    }
}
