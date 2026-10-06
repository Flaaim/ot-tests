<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Notification\Message\MarkAllAsRead;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Command\MarkAllAsRead\Command;
use App\Notification\Command\MarkAllAsRead\Handler;
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

    #[Route('/v1/messages/read-all', name: 'notifications.message.read.all', methods: ['PATCH'])]
    public function __invoke(): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $profileId = $user->getUserIdentifier();

        $command = new Command($profileId);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
