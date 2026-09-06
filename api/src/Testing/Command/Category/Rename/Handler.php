<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Rename;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Category\CategoryId;
use App\Testing\Entity\Category\CategoryRepository;
use App\Testing\Service\SlugGenerator\SlugGeneratorByName;
use DomainException;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly SlugGeneratorByName $slugGenerator,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): void
    {
        $newSlug = $this->slugGenerator->generate($command->name);

        $currentCategory = $this->categories->getById(new CategoryId($command->id));

        if ($this->categories->hasBySlug($newSlug) && $currentCategory->getSlug() !== $newSlug) {
            throw new DomainException('Category with this slug already exists.');
        }

        $currentCategory->rename($command->name, $newSlug);

        $this->flusher->flush();
    }
}
