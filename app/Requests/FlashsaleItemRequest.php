<?php

namespace App\Requests;

final class FlashsaleItemRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'flashsale_id'   => (int) ($payload['flashsale_id'] ?? 0),
            'product_id'     => (int) ($payload['product_id'] ?? 0),
            'stock'          => (int) ($payload['stock'] ?? 0),
            'sold'           => (int) ($payload['sold'] ?? 0),
            'discount_type'  => (string) ($payload['discount_type'] ?? 'fixed'),
            'discount_value' => (float) ($payload['discount_value'] ?? 0),
            'sort_order'     => (int) ($payload['sort_order'] ?? 0),
            'status'         => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
