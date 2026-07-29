<?php

namespace App\Validation;

final class FlashsaleItemRequestRules
{
    public static function save(): array
    {
        return [
            'flashsale_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Flashsale wajib dipilih.',
                    'is_natural_no_zero' => 'Flashsale tidak valid.',
                ],
            ],
            'product_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Produk wajib dipilih.',
                    'is_natural_no_zero' => 'Produk tidak valid.',
                ],
            ],
            'stock' => [
                'rules'  => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required'              => 'Stok wajib diisi.',
                    'integer'               => 'Stok harus berupa angka.',
                    'greater_than_equal_to' => 'Stok tidak boleh negatif.',
                ],
            ],
            'discount_type' => [
                'rules'  => 'required|in_list[fixed,percent]',
                'errors' => [
                    'required' => 'Tipe diskon wajib dipilih.',
                    'in_list'  => 'Tipe diskon tidak valid.',
                ],
            ],
            'discount_value' => [
                'rules'  => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required'      => 'Nilai diskon wajib diisi.',
                    'decimal'       => 'Nilai diskon harus berupa angka.',
                    'greater_than'  => 'Nilai diskon harus lebih dari 0.',
                ],
            ],
            'sort_order' => [
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
