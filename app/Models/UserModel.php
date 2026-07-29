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

    /**
     * Admin listing — search by username/email/phone, filter by status/level.
     */
    public function paginatedList(string $keyword = '', string $status = '', string $level = '', int $perPage = 20): array
    {
        $builder = $this->orderBy('id', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('username', $keyword)
                ->orLike('email', $keyword)
                ->orLike('phone', $keyword)
                ->groupEnd();
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        if ($level !== '') {
            $builder->where('level', $level);
        }

        $items = $builder->paginate($perPage);

        return [
            'items' => $items,
            'pager' => $this->pager,
        ];
    }
}
