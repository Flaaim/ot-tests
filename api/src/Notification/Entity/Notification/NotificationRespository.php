<?php

declare(strict_types=1);

namespace App\Notification\Entity\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class NotificationRespository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Notification::class);
    }

    public function add(Notification $notification): void
    {
        $this->em->persist($notification);
    }

    public function get(NotificationId $id): Notification
    {
        $notification = $this->repo->find($id);
        if (null === $notification) {
            throw new \DomainException('Notification was not found.');
        }
        return $notification;
    }
}
