<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

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

    public function get(AttemptId $id): Attempt
    {
        $attempt = $this->repo->find($id);
        if (null === $attempt) {
            throw new DomainException('Attempt not found.');
        }
        /** @var Attempt $attempt */
        return $attempt;
    }
}
