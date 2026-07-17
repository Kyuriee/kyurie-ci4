<?php

namespace App\Requests;

use CodeIgniter\HTTP\RequestInterface;

final class AuthRequest
{
    public static function login(RequestInterface $request): array
    {
        return [
            'username' => trim((string) $request->getPost('username')),
            'password' => (string) $request->getPost('password'),
            'remember' => (bool) $request->getPost('remember'),
        ];
    }

    public static function register(RequestInterface $request): array
    {
        return [
            'username' => trim((string) $request->getPost('username')),
            'email'    => trim((string) $request->getPost('email')),
            'password' => (string) $request->getPost('password'),
            'phone'    => trim((string) $request->getPost('phone')),
        ];
    }

    public static function forgot(RequestInterface $request): array
    {
        return [
            'email' => trim((string) $request->getPost('email')),
        ];
    }

    public static function reset(RequestInterface $request): array
    {
        return [
            'password' => (string) $request->getPost('password'),
        ];
    }
}
