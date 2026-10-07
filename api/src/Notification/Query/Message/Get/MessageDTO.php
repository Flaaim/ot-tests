<?php

declare(strict_types=1);

namespace App\Notification\Query\Message\Get;

final readonly class MessageDTO
{
    public function __construct(
        public string $messageId,
        public string $subject,
        public string $message,
        public string $status,
        public string $createdAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            messageId: $data['message_id'],
            subject: $data['subject'],
            message: $data['message'],
            status: $data['status'],
            createdAt: $data['created_at'],
        );
    }
}
