<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'name',
        'level',
        'status',
        'last_login_at',
    ];

    public function get_by_username(string $username): array
    {
        $data = $this->where('username', $username)->first();

        return $data ?? [];
    }

    public function verify_login(string $username, string $password): array
    {
        $admin = $this->where('username', $username)
            ->where('status', 'On')
            ->first();

        if (! $admin) {
            return [];
        }

        if (! password_verify($password, $admin['password'])) {
            return [];
        }

        $this->update($admin['id'], [
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        return $admin;
    }
}