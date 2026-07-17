<?php

namespace App\Requests;

use CodeIgniter\HTTP\RequestInterface;

final class AdminAuthRequest
{
    public static function login(RequestInterface $request): array
    {
        return [
            'username' => trim((string) $request->getPost('username')),
            'password' => (string) $request->getPost('password'),
        ];
    }
}
