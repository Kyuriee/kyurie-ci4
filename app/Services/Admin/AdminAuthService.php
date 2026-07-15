<?php

namespace App\Services\Admin;

use App\Models\AdminModel;
use App\Services\BaseService;

class AdminAuthService extends BaseService
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = model(AdminModel::class);
    }

    public function login(string $username, string $password): array
    {
        return $this->safeCall(function () use ($username, $password) {
            $username = trim($username);

            if ($username === '' || $password === '') {
                return $this->fail('Username dan password wajib diisi');
            }

            $admin = $this->adminModel->verify_login($username, $password);

            if (empty($admin)) {
                return $this->fail('Username atau password salah');
            }

            return $this->success('Login berhasil', [
                'admin_id' => $admin['id'],
                'username' => $admin['username'],
                'name'     => $admin['name'] ?? $admin['username'],
                'level'    => $admin['level'],
            ]);
        }, $this->fail('Gagal memproses login'));
    }

    public function getProfile(int $adminId): array
    {
        return $this->safeCall(
            fn() => $this->adminModel->find($adminId) ?? [],
            []
        );
    }
}
