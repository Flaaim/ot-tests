<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Attempt\Get;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Query\Attempt\Get\Query;
use App\Testing\Query\Attempt\Get\QueryHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Validator $validator,
        private readonly Security $security,
    ) {}

    #[Route('/v1/testing/attempts/{id}', name: 'testing.attempt.get', methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }

        $query = new Query($id);

        $this->validator->validate($query);

        $attempt = $this->handler->handle($query);

        return new JsonResponse($attempt);
    }
}
