<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\User\GetUsersPaginated;

use App\Auth\Query\GetUsersPaginated\Query;
use App\Auth\Query\GetUsersPaginated\QueryHandler;
use App\Infrastructure\Http\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/admin/users', name: 'admin.users.get', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $queryParams = $request->query->all();
        $page = isset($queryParams['page']) && is_numeric($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $limit = isset($queryParams['limit']) && is_numeric($queryParams['limit']) ? (int)$queryParams['limit'] : 15;

        $query = new Query($page, $limit);

        $this->validator->validate($query);

        $users = $this->handler->handle($query);

        return new JsonResponse($users, Response::HTTP_OK);
    }
}
