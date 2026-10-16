<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class JoinByMagicLinkTest extends TestCase
{
    public function testJoin(): void
    {
        $user = User::requestJoinByMagicLink(
            $id = Id::generate(),
            $date = new DateTimeImmutable(),
            $email = new Email('test@app.ru')
        );

        self::assertTrue($user->isWait());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($id, $user->getId());
    }
}
