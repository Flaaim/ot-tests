<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Test\GetByCategory;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Query\Test\GetByCategory\Query;
use App\Testing\Query\Test\GetByCategory\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly QueryHandler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/testing/categories/{slug}/tests', name: 'testing.tests.getByCategory', methods: ['GET'])]
    public function __invoke(string $slug): Response
    {
        $query = new Query($slug);

        $this->validator->validate($query);

        $result = $this->handler->handle($query);
        return new JsonResponse($result, Response::HTTP_OK);
    }
}
