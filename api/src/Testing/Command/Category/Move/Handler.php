<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Move;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Category\CategoryId;
use App\Testing\Entity\Category\CategoryRepository;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $category = $this->categories->getById(new CategoryId($command->id));

        $category->move($command->parentId);

        $this->flusher->flush();
    }
}
