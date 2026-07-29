<?php

namespace App\Requests;

final class FlashsaleRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'title'       => trim((string) ($payload['title'] ?? '')),
            'description' => $payload['description'] ?? null,
            'image'       => $payload['image'] ?? null,
            'date_start'  => $payload['date_start'] ?? null,
            'date_end'    => $payload['date_end'] ?? null,
            'status'      => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
