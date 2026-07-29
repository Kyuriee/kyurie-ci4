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

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — read + moderate. No create (registration stays
     | on the storefront) and no delete (users are referenced by orders).
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', string $status = '', string $level = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->userModel->paginatedList(trim($keyword), trim($status), trim($level), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function findAny(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->userModel->find($id) ?: [];
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('User tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->userModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status user');
        }

        return $this->success('Status user diperbarui', ['status' => $newStatus]);
    }

    /**
     * Admin balance adjustment. Positive amount tops up, negative deducts —
     * caller (controller) is responsible for translating "top up" vs
     * "deduct" UI intent into the signed amount.
     */
    public function adjustBalance(int $id, float $amount): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('User tidak ditemukan');
        }

        if ($amount == 0.0) {
            return $this->fail('Nominal penyesuaian saldo tidak boleh 0');
        }

        if ($amount < 0 && (float) $existing['balance'] + $amount < 0) {
            return $this->fail('Saldo user tidak cukup untuk pengurangan ini');
        }

        $updated = $this->safeCall(fn() => $this->userModel->topUpBalance($id, $amount), false);

        if (! $updated) {
            return $this->fail('Gagal menyesuaikan saldo user');
        }

        return $this->success('Saldo user berhasil disesuaikan');
    }
}
