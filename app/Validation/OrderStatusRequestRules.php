<?php

namespace App\Validation;

final class OrderStatusRequestRules
{
    public static function updateStatus(): array
    {
        return [
            'status' => [
                'rules'  => 'required|in_list[pending,processing,success,failed]',
                'errors' => [
                    'required' => 'Status wajib diisi.',
                    'in_list'  => 'Status tidak valid.',
                ],
            ],
            'note' => [
                'rules'  => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Catatan maksimal 1000 karakter.',
                ],
            ],
        ];
    }
}
