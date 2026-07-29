<?php

namespace App\Requests;

final class CouponRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'code'             => trim((string) ($payload['code'] ?? '')),
            'name'             => trim((string) ($payload['name'] ?? '')),
            'discount_percent' => $payload['discount_percent'] ?? 0,
            'discount_nominal' => $payload['discount_nominal'] ?? 0,
            'max_discount'     => $payload['max_discount'] ?? '',
            'min_transaction'  => $payload['min_transaction'] ?? 0,
            'type'             => (string) ($payload['type'] ?? 'general'),
            'level_csv'        => $payload['level_csv'] ?? null,
            'game_csv'         => $payload['game_csv'] ?? null,
            'product_csv'      => $payload['product_csv'] ?? null,
            'max_per_guest'    => $payload['max_per_guest'] ?? '',
            'max_per_user'     => $payload['max_per_user'] ?? '',
            'max_global'       => $payload['max_global'] ?? '',
            'max_per_daily'    => $payload['max_per_daily'] ?? '',
            'valid_from'       => $payload['valid_from'] ?? null,
            'valid_until'      => $payload['valid_until'] ?? null,
            'status'           => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
