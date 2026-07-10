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
}
