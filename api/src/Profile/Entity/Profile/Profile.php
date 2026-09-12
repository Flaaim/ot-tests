<?php

declare(strict_types=1);

namespace App\Profile\Entity\Profile;

use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;

final class Profile implements AggregateRoot
{
    use EventTrait;

    public function __construct(
        private ProfileId $id,
        private Email $email,
        private Role $role,
        private Status $status,
        private ?string $name = null,
        private ?string $surname = null
    ) {}

    public function getId(): ProfileId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }
}
