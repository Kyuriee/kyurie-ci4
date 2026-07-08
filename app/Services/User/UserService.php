<?php

namespace App\Services\User;

use App\Models\UserModel;
use App\Services\baseService;

class UserService extends baseService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    public function getProfile(int $userId): array
    {
        return $this->userModel->find($userId);
    }

    public function updateProfile(int $userId, array $data): array
    {
        $allowed = ['email', 'phone'];
        $update  = array_intersect_key($data, array_flip($allowed));

        if (empty($update)) {
            return [
                'success' => false,
                'message' => 'Tidak ada data yang diubah',
            ];
        }

        $this->userModel->update($userId, $update);

        return [
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
        ];
    }
}
