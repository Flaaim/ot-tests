<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Notification\Message\MarkAsRead;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Command\MarkAsRead\Command;
use App\Notification\Command\MarkAsRead\Handler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class RequestAction
{
    public function __construct(
        private Handler $handler,
        private Validator $validator,
        private Security $security,
    ) {}

    #[Route('/v1/messages/{id}', name: 'notifications.message.mark.read', methods: ['PATCH'])]
    public function __invoke(string $id): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $profileId = $user->getUserIdentifier();

        $command = new Command($id, $profileId);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
