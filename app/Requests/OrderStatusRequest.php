<?php

namespace App\Requests;

final class OrderStatusRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'status' => (string) ($payload['status'] ?? ''),
            'note'   => $payload['note'] ?? null,
        ];
    }
}
