<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Category\GetTree;

use App\Testing\Query\Category\GetAll\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
    ) {}

    #[Route('/v1/testing/categories', name: 'testing.categories.get.tree', methods: ['GET'])]
    public function __invoke(): Response
    {
        $result = $this->handler->handle();

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
