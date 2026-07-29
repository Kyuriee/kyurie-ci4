<?php

namespace App\Requests;

final class BannerRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'title'      => trim((string) ($payload['title'] ?? '')),
            'subtitle'   => $payload['subtitle'] ?? null,
            'image'      => $payload['image'] ?? null,
            'link'       => $payload['link'] ?? null,
            'sort'       => (int) ($payload['sort'] ?? 0),
            'status'     => (string) ($payload['status'] ?? 'On'),
            'date_start' => $payload['date_start'] ?? null,
            'date_end'   => $payload['date_end'] ?? null,
        ];
    }
}
