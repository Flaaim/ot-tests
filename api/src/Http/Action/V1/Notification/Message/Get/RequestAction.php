<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Notification\Message\Get;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Query\Message\Get\Query;
use App\Notification\Query\Message\Get\QueryHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class RequestAction
{
    public function __construct(
        private QueryHandler $handler,
        private Validator $validator,
        private Security $security,
    ) {}

    #[Route('/v1/messages', name: 'notifications.message.list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $profileId = $user->getUserIdentifier();

        $queryParams = $request->query->all();
        $limit = isset($queryParams['limit']) && is_numeric($queryParams['limit']) ? (int)$queryParams['limit'] : 5;
        $page = isset($queryParams['page']) && is_numeric($queryParams['page']) ? (int)$queryParams['page'] : 1;

        $query = new Query($profileId, $page, $limit);

        $this->validator->validate($query);

        $result = $this->handler->handle($query);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
