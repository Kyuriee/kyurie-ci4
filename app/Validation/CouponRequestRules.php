<?php

namespace App\Validation;

final class CouponRequestRules
{
    public static function save(): array
    {
        return [
            'code' => [
                'rules'  => 'required|max_length[50]|alpha_numeric',
                'errors' => [
                    'required'      => 'Kode kupon wajib diisi.',
                    'max_length'    => 'Kode kupon maksimal 50 karakter.',
                    'alpha_numeric' => 'Kode kupon hanya boleh huruf dan angka.',
                ],
            ],
            'name' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama kupon wajib diisi.',
                    'min_length' => 'Nama kupon minimal 2 karakter.',
                    'max_length' => 'Nama kupon maksimal 150 karakter.',
                ],
            ],
            'discount_percent' => [
                'rules'  => 'permit_empty|decimal',
                'errors' => [
                    'decimal' => 'Persen diskon harus berupa angka.',
                ],
            ],
            'discount_nominal' => [
                'rules'  => 'permit_empty|decimal',
                'errors' => [
                    'decimal' => 'Nominal diskon harus berupa angka.',
                ],
            ],
            'min_transaction' => [
                'rules'  => 'permit_empty|decimal',
                'errors' => [
                    'decimal' => 'Minimal transaksi harus berupa angka.',
                ],
            ],
            'type' => [
                'rules'  => 'permit_empty|in_list[general,custom]',
                'errors' => [
                    'in_list' => 'Tipe kupon tidak valid.',
                ],
            ],
            'valid_from' => [
                'rules'  => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Tanggal mulai tidak valid.',
                ],
            ],
            'valid_until' => [
                'rules'  => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Tanggal berakhir tidak valid.',
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
