<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Notification\Launch;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Command\Launch\Command;
use App\Notification\Command\Launch\Handler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final readonly class RequestAction
{
    public function __construct(
        private Handler $handler,
        private Validator $validator
    ) {}

    #[Route('/v1/admin/notifications', name: 'admin.notifications.launch', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $body = $request->toArray();
        $subject = (string)($body['subject'] ?? '');
        $message = (string)($body['message'] ?? '');

        $command = new Command($subject, $message);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(201, Response::HTTP_CREATED);
    }
}
