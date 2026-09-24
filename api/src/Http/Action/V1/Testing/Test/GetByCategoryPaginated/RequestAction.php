<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Test\GetByCategoryPaginated;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Query\Test\GetByCategoryPaginated\Query;
use App\Testing\Query\Test\GetByCategoryPaginated\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/testing/categories/{slug}/tests', name: 'testing.tests.getByCategory', methods: ['GET'])]
    public function __invoke(string $slug, Request $request): Response
    {
        $queryParams = $request->query->all();
        $page = isset($queryParams['page']) && is_numeric($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $limit = isset($queryParams['limit']) && is_numeric($queryParams['limit']) ? (int)$queryParams['limit'] : 15;

        $query = new Query($slug, $page, $limit);

        $this->validator->validate($query);

        $result = $this->handler->handle($query);
        return new JsonResponse($result, Response::HTTP_OK);
    }
}
