<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = false;

    public function verifyLogin(string $identifier, string $password): array
    {
        $user = $this->where('status', 'On')
            ->groupStart()
                ->where('username', $identifier)
                ->orWhere('email', $identifier)
            ->groupEnd()
            ->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return [];
        }

        return $user;
    }

    public function getByUsername(string $username): array
    {
        $data = $this->where('username', $username)->first();

        return $data ?? [];
    }

    public function getByEmail(string $email): array
    {
        $data = $this->where('email', $email)->first();

        return $data ?? [];
    }

    public function insert($data = null, bool $returnID = true)
    {
        return parent::insert($data, $returnID);
    }
}
