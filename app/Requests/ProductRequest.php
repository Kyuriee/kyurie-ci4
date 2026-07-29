<?php

namespace App\Requests;

final class ProductRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'games_id'  => (int) ($payload['games_id'] ?? 0),
            'product'   => trim((string) ($payload['product'] ?? '')),
            'sku'       => trim((string) ($payload['sku'] ?? '')),
            'provider'  => trim((string) ($payload['provider'] ?? '')),
            'raw_price' => (float) ($payload['raw_price'] ?? 0),
            'price'     => (float) ($payload['price'] ?? 0),
            'sort'      => (int) ($payload['sort'] ?? 0),
            'status'    => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
