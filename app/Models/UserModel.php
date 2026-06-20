<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = false;

    public function find($id = null): array
    {
        $data = parent::find($id);

        return $data ?? [];
    }


    public function topUpBalance(int $userId, float $amount): bool
    {
        return $this->db->table('users')
            ->where('id', $userId)
            ->set('balance', "balance + $amount", false)
            ->update();
    }
}
