<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Testing\Category\GetOne;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Query\Category\GetOne\Query;
use App\Testing\Query\Category\GetOne\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/admin/testing/categories/{id}', name: 'admin.testing.categories.get.one', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(string $id): Response
    {
        $command = new Query($id);

        $this->validator->validate($command);

        $result = $this->handler->handle($command);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
