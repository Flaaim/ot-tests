<?php

declare(strict_types=1);

namespace App\Profile\Entity\Profile;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class ProfileRepository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Profile::class);
    }

    public function add(Profile $profile): void
    {
        $this->em->persist($profile);
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function remove(Profile $profile): void
    {
        $this->em->remove($profile);
    }

    public function get(ProfileId $id): Profile
    {
        $profile = $this->repo->find($id->getValue());
        if (null === $profile) {
            throw new DomainException('Profile not found.');
        }
        return $profile;
    }
}
