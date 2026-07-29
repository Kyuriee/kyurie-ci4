<?php

namespace App\Validation;

final class ProductRequestRules
{
    public static function save(): array
    {
        return [
            'games_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Game wajib dipilih.',
                    'is_natural_no_zero' => 'Game tidak valid.',
                ],
            ],
            'product' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama produk wajib diisi.',
                    'min_length' => 'Nama produk minimal 2 karakter.',
                    'max_length' => 'Nama produk maksimal 150 karakter.',
                ],
            ],
            'sku' => [
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required'   => 'SKU wajib diisi.',
                    'max_length' => 'SKU maksimal 100 karakter.',
                ],
            ],
            'provider' => [
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Provider maksimal 50 karakter.',
                ],
            ],
            'raw_price' => [
                'rules'  => 'permit_empty|decimal',
                'errors' => [
                    'decimal' => 'Harga modal harus berupa angka.',
                ],
            ],
            'price' => [
                'rules'  => 'required|decimal|greater_than_equal_to[0]',
                'errors' => [
                    'required'                => 'Harga jual wajib diisi.',
                    'decimal'                 => 'Harga jual harus berupa angka.',
                    'greater_than_equal_to'   => 'Harga jual tidak boleh negatif.',
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
