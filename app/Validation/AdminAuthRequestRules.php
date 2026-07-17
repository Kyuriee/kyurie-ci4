<?php

namespace App\Validation;

final class AdminAuthRequestRules
{
    public static function login(): array
    {
        return [
            'username' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Username wajib diisi.',
                    'min_length' => 'Username minimal 3 karakter.',
                    'max_length' => 'Username maksimal 100 karakter.',
                ],
            ],

            'password' => [
                'rules' => 'required|min_length[6]|max_length[255]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                    'max_length' => 'Password terlalu panjang.',
                ],
            ],
        ];
    }
}
