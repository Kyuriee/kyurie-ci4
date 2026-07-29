<?php

namespace App\Validation;

final class BannerRequestRules
{
    public static function save(): array
    {
        return [
            'title' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Judul banner wajib diisi.',
                    'min_length' => 'Judul banner minimal 2 karakter.',
                    'max_length' => 'Judul banner maksimal 150 karakter.',
                ],
            ],
            'link' => [
                'rules'  => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Link maksimal 255 karakter.',
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
            'date_start' => [
                'rules'  => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Tanggal mulai tidak valid.',
                ],
            ],
            'date_end' => [
                'rules'  => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Tanggal berakhir tidak valid.',
                ],
            ],
        ];
    }
}
