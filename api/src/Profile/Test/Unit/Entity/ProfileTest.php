<?php

declare(strict_types=1);

namespace App\Profile\Test\Unit\Entity;

use App\Profile\Entity\Profile\Email;
use App\Profile\Entity\Profile\Profile;
use App\Profile\Entity\Profile\ProfileId;
use App\Profile\Entity\Profile\Role;
use App\Profile\Entity\Profile\Status;
use PHPUnit\Framework\TestCase;

final class ProfileTest extends TestCase
{
    public function testProfile(): void
    {
        $profile = new Profile(
            $id = ProfileId::generate(),
            $email = new Email('email@test.ru'),
            $role = Role::user(),
            $status = Status::ok(),
            $name = 'John',
            $surname = 'Doue'
        );
        $this->assertEquals($id, $profile->getId());
        $this->assertEquals($email, $profile->getEmail());
        $this->assertEquals($role, $profile->getRole());
        $this->assertEquals($status, $profile->getStatus());
        $this->assertEquals($name, $profile->getName());
        $this->assertEquals($surname, $profile->getSurname());
    }
}
