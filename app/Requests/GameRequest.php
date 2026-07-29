<?php

namespace App\Requests;

final class GameRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'game_category_id' => (int) ($payload['game_category_id'] ?? 0),
            'games'            => trim((string) ($payload['games'] ?? '')),
            'slug'             => trim((string) ($payload['slug'] ?? '')),
            'code'             => trim((string) ($payload['code'] ?? '')),
            'provider'         => trim((string) ($payload['provider'] ?? '')),
            'publisher'        => trim((string) ($payload['publisher'] ?? '')),
            'image'            => $payload['image'] ?? null,
            'banner'           => $payload['banner'] ?? null,
            'description'      => (string) ($payload['description'] ?? ''),
            'target'           => (string) ($payload['target'] ?? 'default'),
            'input_custom'     => $payload['input_custom'] ?? null,
            'is_popular'       => (string) ($payload['is_popular'] ?? 'N'),
            'sort'             => (int) ($payload['sort'] ?? 0),
            'status'           => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
