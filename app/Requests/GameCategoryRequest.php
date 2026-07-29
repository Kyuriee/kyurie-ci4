<?php

namespace App\Requests;

final class GameCategoryRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'category' => trim((string) ($payload['category'] ?? '')),
            'slug'     => trim((string) ($payload['slug'] ?? '')),
            'image'    => $payload['image'] ?? null,
            'sort'     => (int) ($payload['sort'] ?? 0),
            'status'   => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
