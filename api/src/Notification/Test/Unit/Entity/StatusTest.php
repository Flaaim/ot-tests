<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity;

use App\Notification\Entity\Status;
use PHPUnit\Framework\TestCase;

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
