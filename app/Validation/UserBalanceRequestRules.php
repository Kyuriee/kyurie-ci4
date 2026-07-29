<?php

namespace App\Validation;

final class UserBalanceRequestRules
{
    public static function adjust(): array
    {
        return [
            'amount' => [
                'rules'  => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required'     => 'Nominal wajib diisi.',
                    'decimal'      => 'Nominal harus berupa angka.',
                    'greater_than' => 'Nominal harus lebih dari 0.',
                ],
            ],
            'type' => [
                'rules'  => 'required|in_list[topup,deduct]',
                'errors' => [
                    'required' => 'Tipe penyesuaian wajib dipilih.',
                    'in_list'  => 'Tipe penyesuaian tidak valid.',
                ],
            ],
        ];
    }
}
