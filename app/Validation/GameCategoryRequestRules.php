<?php

namespace App\Validation;

final class GameCategoryRequestRules
{
    public static function save(): array
    {
        return [
            'category' => [
                'rules'  => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama kategori wajib diisi.',
                    'min_length' => 'Nama kategori minimal 2 karakter.',
                    'max_length' => 'Nama kategori maksimal 100 karakter.',
                ],
            ],
            'slug' => [
                'rules'  => 'permit_empty|max_length[150]',
                'errors' => [
                    'max_length' => 'Slug maksimal 150 karakter.',
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
