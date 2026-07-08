<?php

namespace App\Validation;

class AuthRequestRules
{
    public static function login(): array
    {
        return [
            'username' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Username atau email wajib diisi.',
                    'min_length' => 'Minimal 3 karakter.',
                    'max_length' => 'Maksimal 100 karakter.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                ],
            ],
        ];
    }

    public static function register(): array
    {
        return [
            'username' => [
                'rules'  => 'required|alpha_numeric|min_length[4]|max_length[100]|is_unique[users.username]',
                'errors' => [
                    'required'      => 'Username wajib diisi.',
                    'alpha_numeric' => 'Username hanya boleh berisi huruf dan angka.',
                    'min_length'    => 'Username minimal 4 karakter.',
                    'is_unique'     => 'Username ini sudah terdaftar, gunakan yang lain.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email ini sudah terdaftar.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches'  => 'Konfirmasi password tidak sama dengan password.',
                ],
            ],
            'phone' => [
                'rules'  => 'permit_empty|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'numeric'    => 'Nomor telepon harus berupa angka.',
                    'min_length' => 'Nomor telepon minimal 10 angka.',
                    'max_length' => 'Nomor telepon maksimal 15 angka.',
                ],
            ],
        ];
    }

    public static function forgot(): array
    {
        return [
            'email' => 'required|valid_email',
        ];
    }

    public static function reset(): array
    {
        return [
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];
    }
}
