<?php

declare(strict_types=1);

namespace App\Testing\Entity\Category;

use App\SharedDomain\AggregateRoot;
use App\SharedDomain\Event\EventTrait;

final class Category implements AggregateRoot
{
    use EventTrait;

    public function __construct(
        private CategoryId $id,
        private string $name,
        private string $description,
        private string $slug,
        private ?string $parentId = null,
    ) {}

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }
}
