<?php

namespace App\Requests;

final class PaymentMethodRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'name'     => trim((string) ($payload['name'] ?? '')),
            'provider' => trim((string) ($payload['provider'] ?? '')),
            'code'     => trim((string) ($payload['code'] ?? '')),
            'type'     => trim((string) ($payload['type'] ?? '')),
            'config'   => $payload['config'] ?? null,
            'image'    => $payload['image'] ?? null,
            'sort'     => (int) ($payload['sort'] ?? 0),
            'status'   => (string) ($payload['status'] ?? 'On'),
        ];
    }
}
