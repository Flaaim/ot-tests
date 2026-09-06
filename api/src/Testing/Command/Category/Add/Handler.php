<?php

declare(strict_types=1);

namespace App\Testing\Command\Category\Add;

use App\Infrastructure\Doctrine\Flusher;
use App\Testing\Entity\Category\Category;
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
        $slug = $this->slugGenerator->generate($command->name);
        if ($this->categories->hasBySlug($slug)) {
            throw new DomainException("Category with slug {$slug} already exists.");
        }

        $parentCategory = null;
        if (null !== $command->parentId) {
            $parentCategory = $this->categories->getById(new CategoryId($command->parentId));
        }

        $category = new Category(
            CategoryId::generate(),
            $command->name,
            $command->description,
            $slug,
            $parentCategory?->getId()->getValue(),
        );

        $this->categories->add($category);

        $this->flusher->flush();
    }
}
