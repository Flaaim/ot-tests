<?php

declare(strict_types=1);

namespace App\Notification\Entity\Message;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

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

    public function remove(string $notificationId): void
    {
        $this->repo->createQueryBuilder('t')
            ->delete(Message::class, 't')
            ->where('t.notificationId = :notificationId')
            ->setParameter(':notificationId', $notificationId)
            ->getQuery()
            ->execute();
    }

    public function removeByProfile(string $profileId): void
    {
        $this->repo->createQueryBuilder('t')
            ->delete(Message::class, 't')
            ->where('t.profileId = :profileId')
            ->setParameter(':profileId', $profileId)
            ->getQuery()
            ->execute();
    }

    public function get(MessageId $messageId): Message
    {
        $message = $this->repo->find($messageId);
        if (null === $message) {
            throw new DomainException('Message not found.');
        }
        return $message;
    }

    public function markAllAsRead(string $profileId): void
    {
        $this->repo->createQueryBuilder('t')
            ->update(Message::class, 't')
            ->set('t.status', ':status')
            ->where('t.profileId = :profileId')
            ->andWhere('t.status = :isNotRead')
            ->setParameter('profileId', $profileId)
            ->setParameter('status', Status::READ)
            ->setParameter('isNotRead', Status::NOT_READ)
            ->getQuery()
            ->execute();
    }
}
