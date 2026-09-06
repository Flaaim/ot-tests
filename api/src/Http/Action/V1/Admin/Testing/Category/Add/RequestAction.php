<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Testing\Category\Add;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Category\Add\Command;
use App\Testing\Command\Category\Add\Handler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/admin/testing/categories', name: 'admin.testing.categories.add', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $body = $request->toArray();
        $name = $body['name'] ?? '';
        $description = $body['description'] ?? '';
        $parentId = $body['parent_id'] ?? null;

        $command = new Command($name, $description, $parentId);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
