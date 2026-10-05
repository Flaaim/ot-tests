<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

use App\Notification\Entity\Notification\NotificationId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class MessageRepository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Message::class);
    }

    public function add(Message $message): void
    {
        $this->em->persist($message);
    }

    public function clear(): void
    {
        $this->em->clear();
    }

    public function remove(NotificationId $notificationId): void
    {
        $this->repo->createQueryBuilder('t')
            ->delete(Message::class, 't')
            ->where('t.notificationId = :notificationId')
            ->setParameter(':notificationId', $notificationId->getValue())
            ->getQuery()
            ->execute();
    }
}
