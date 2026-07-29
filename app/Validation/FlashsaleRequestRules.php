<?php

namespace App\Validation;

final class FlashsaleRequestRules
{
    public static function save(): array
    {
        return [
            'title' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Judul flashsale wajib diisi.',
                    'min_length' => 'Judul flashsale minimal 2 karakter.',
                    'max_length' => 'Judul flashsale maksimal 150 karakter.',
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
            'status' => [
                'rules'  => 'permit_empty|in_list[On,Off]',
                'errors' => [
                    'in_list' => 'Status tidak valid.',
                ],
            ],
        ];
    }
}
