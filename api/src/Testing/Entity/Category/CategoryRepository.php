<?php

declare(strict_types=1);

namespace App\Testing\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class CategoryRepository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Category::class);
    }

    public function hasBySlug(string $slug): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.slug = :slug')
            ->setParameter(':slug', $slug)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(Category $category): void
    {
        $this->em->persist($category);
    }

    public function getById(CategoryId $id): Category
    {
        $category = $this->repo->find($id);
        if (null === $category) {
            throw new DomainException('Category not found.');
        }
        /** @var Category $category */
        return $category;
    }

    public function hasChildren(string $parentId): bool
    {
        return $this->repo->count(['parentId' => $parentId]) > 0;
    }

    public function remove(Category $category): void
    {
        $this->em->remove($category);
    }
}
