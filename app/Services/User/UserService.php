<?php

namespace App\Services\User;

use App\Models\UserModel;
use App\Services\BaseService;

class UserService extends BaseService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    public function getCurrentUser(?int $userId = null, ?string $rememberToken = null): ?array
    {
        return $this->safeCall(function () use ($userId, $rememberToken) {
            if ($userId !== null) {
                $user = $this->userModel->find($userId);
            } elseif ($rememberToken !== null && $rememberToken !== '') {
                $user = $this->userModel
                    ->where('remember_token', $rememberToken)
                    ->first();
            } else {
                return null;
            }

            if (! $user || $user['status'] !== 'On') {
                return null;
            }

            return [
                'id'       => (int) $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'phone'    => $user['phone'],
                'balance'  => (float) $user['balance'],
                'level'    => $user['level'],
            ];
        }, null);
    }

    public function getProfile(int $userId): array
    {
        return $this->safeCall(
            fn() => $this->userModel->find($userId) ?? [],
            []
        );
    }

    public function updateProfile(int $userId, array $data): array
    {
        return $this->safeCall(function () use ($userId, $data) {
            $allowed = ['email', 'phone'];
            $update  = array_intersect_key($data, array_flip($allowed));

            if (empty($update)) {
                return $this->fail('Tidak ada data yang diubah');
            }

            $this->userModel->update($userId, $update);

            return $this->success('Profil berhasil diperbarui');
        }, $this->fail('Gagal memperbarui profil'));
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        return $this->safeCall(function () use ($userId, $currentPassword, $newPassword) {
            $user = $this->userModel->find($userId);

            if (empty($user)) {
                return $this->fail('User tidak ditemukan');
            }

            if (! password_verify($currentPassword, $user['password'])) {
                return $this->fail('Password saat ini salah');
            }

            if (strlen($newPassword) < 8) {
                return $this->fail('Password baru minimal 8 karakter');
            }

            $this->userModel->update($userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);

            return $this->success('Password berhasil diperbarui');
        }, $this->fail('Gagal memperbarui password'));
    }
}
