<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit\Entity\Message;

use App\Notification\Entity\Message\MessageId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class MessageIdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new MessageId($value = Uuid::uuid4()->toString());

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $id = new MessageId(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testGenerate(): void
    {
        $id = MessageId::generate();

        self::assertNotEmpty($id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MessageId('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MessageId('');
    }
}
