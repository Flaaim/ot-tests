<?php

declare(strict_types=1);

namespace App\TgNotifier\Entity\Contact;

use Doctrine\ORM\EntityRepository;

final class ContactRepository
{
    private readonly EntityRepository $repo;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $em->getRepository(Contact::class);
    }


}
