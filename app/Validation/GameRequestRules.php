<?php

namespace App\Validation;

final class GameRequestRules
{
    public static function save(): array
    {
        return [
            'game_category_id' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Kategori wajib dipilih.',
                    'is_natural_no_zero' => 'Kategori tidak valid.',
                ],
            ],
            'games' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama game wajib diisi.',
                    'min_length' => 'Nama game minimal 2 karakter.',
                    'max_length' => 'Nama game maksimal 150 karakter.',
                ],
            ],
            'slug' => [
                'rules'  => 'permit_empty|max_length[150]',
                'errors' => [
                    'max_length' => 'Slug maksimal 150 karakter.',
                ],
            ],
            'code' => [
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Kode maksimal 50 karakter.',
                ],
            ],
            'provider' => [
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Provider maksimal 50 karakter.',
                ],
            ],
            'publisher' => [
                'rules'  => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Publisher maksimal 255 karakter.',
                ],
            ],
            'target' => [
                'rules'  => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Target maksimal 255 karakter.',
                ],
            ],
            'is_popular' => [
                'rules'  => 'permit_empty|in_list[Y,N]',
                'errors' => [
                    'in_list' => 'Nilai populer tidak valid.',
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
