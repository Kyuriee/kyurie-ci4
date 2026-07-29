<?php

namespace App\Validation;

final class PaymentMethodRequestRules
{
    public static function save(): array
    {
        return [
            'name' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama metode pembayaran wajib diisi.',
                    'min_length' => 'Nama minimal 2 karakter.',
                    'max_length' => 'Nama maksimal 150 karakter.',
                ],
            ],
            'provider' => [
                'rules'  => 'required|max_length[50]',
                'errors' => [
                    'required'   => 'Provider wajib diisi.',
                    'max_length' => 'Provider maksimal 50 karakter.',
                ],
            ],
            'code' => [
                'rules'  => 'required|max_length[50]',
                'errors' => [
                    'required'   => 'Kode wajib diisi.',
                    'max_length' => 'Kode maksimal 50 karakter.',
                ],
            ],
            'type' => [
                'rules'  => 'required|max_length[50]',
                'errors' => [
                    'required'   => 'Tipe wajib diisi.',
                    'max_length' => 'Tipe maksimal 50 karakter.',
                ],
            ],
            'sort' => [
                'rules'  => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Urutan harus berupa angka.',
                ],
            ],
            'status' => [
                'rules'  => 'permit_empty|in_list[On,Off]',
                'errors' => [
                    'in_list' => 'Status tidak valid.',
                ],
            ],
        ];
    }
}
