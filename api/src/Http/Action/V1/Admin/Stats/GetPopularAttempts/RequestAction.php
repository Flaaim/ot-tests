<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Stats\GetPopularAttempts;

use App\Admin\Stats\Query\PopularTests\QueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class RequestAction
{
    public function __construct(
        private QueryHandler $queryHandler,
    ) {}

    #[Route('/v1/admin/stats/popular-attempts', name: 'admin.stats.popular.attempt', methods: ['GET'])]
    public function __invoke(): Response
    {
        $result = $this->queryHandler->handle();

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
