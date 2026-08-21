<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class AttemptRepository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Attempt::class);
    }

    public function add(Attempt $attempt): void
    {
        $this->em->persist($attempt);
    }
}
