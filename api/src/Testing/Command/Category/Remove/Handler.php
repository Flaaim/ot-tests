<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Remove;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Category\CategoryId;
use App\Testing\Entity\Category\CategoryRepository;
use App\Testing\Entity\Test\TestRepository;
use DomainException;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly TestRepository $tests,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $category = $this->categories->getById(new CategoryId($command->id));

        if ($this->categories->hasChildren($category->getId()->getValue())) {
            throw new DomainException('Невозможно удалить категорию: она содержит подкатегории.');
        }

        if ($this->tests->hasByCategoryId($category->getId()->getValue())) {
            throw new DomainException('Невозможно удалить категорию: к ней привязаны тесты.');
        }
        $this->categories->remove($category);

        $this->flusher->flush();
    }
}
