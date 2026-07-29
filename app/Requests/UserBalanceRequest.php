<?php

namespace App\Requests;

final class UserBalanceRequest
{
    public static function fromPayload(array $payload): array
    {
        return [
            'amount' => (float) ($payload['amount'] ?? 0),
            'type'   => (string) ($payload['type'] ?? 'topup'), // 'topup' | 'deduct'
        ];
    }
}
