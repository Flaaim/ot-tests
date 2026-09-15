<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Profile\Get;

use App\OAuth\Entity\UserAdapter;
use App\Profile\Query\Get\Query;
use App\Profile\Query\Get\QueryHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Security $security,
    ) {}

    #[Route('/v1/me', name: 'user.me', methods: ['GET'])]
    public function __invoke(): Response
    {
        /** @var UserAdapter|null $currentUser */
        $currentUser = $this->security->getUser();

        if (null === $currentUser) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $userId = $currentUser->getUserIdentifier();

        $query = new Query($userId);

        $profile = $this->handler->handle($query);

        return new JsonResponse($profile, Response::HTTP_OK);
    }
}
