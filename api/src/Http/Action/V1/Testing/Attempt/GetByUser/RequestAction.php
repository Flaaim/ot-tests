<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Attempt\GetByUser;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Query\Attempt\GetByUser\Query;
use App\Testing\Query\Attempt\GetByUser\QueryHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $queryHandler,
        private readonly Validator $validator,
        private readonly Security $security,
    ) {}

    #[Route('/v1/attempts/profile', name: 'testing.attempts.profile', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $queryParams = $request->query->all();
        $page = isset($queryParams['page']) && is_numeric($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $limit = isset($queryParams['limit']) && is_numeric($queryParams['limit']) ? (int)$queryParams['limit'] : 15;

        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }

        $userId = $user->getUserIdentifier();

        $query = new Query($userId, $page, $limit);

        $this->validator->validate($query);

        $result = $this->queryHandler->handle($query);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
