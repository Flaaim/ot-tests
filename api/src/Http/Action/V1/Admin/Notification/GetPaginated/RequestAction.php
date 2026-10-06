<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Notification\GetPaginated;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Query\Notification\GetPaginated\Query;
use App\Notification\Query\Notification\GetPaginated\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final readonly class RequestAction
{
    public function __construct(
        private QueryHandler $handler,
        private Validator $validator,
    ) {}

    #[Route('/v1/admin/notifications', name: 'admin.notifications.get.paginated', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $queryParams = $request->query->all();
        $page = isset($queryParams['page']) && is_numeric($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $limit = isset($queryParams['limit']) && is_numeric($queryParams['limit']) ? (int)$queryParams['limit'] : 15;

        $query = new Query($page, $limit);

        $this->validator->validate($query);

        $notifications = $this->handler->handle($query);

        return new JsonResponse($notifications, Response::HTTP_OK);
    }
}
