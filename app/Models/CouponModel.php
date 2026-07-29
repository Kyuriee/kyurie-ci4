<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table         = 'coupons';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'code',
        'name',
        'discount_percent',
        'discount_nominal',
        'max_discount',
        'min_transaction',
        'type',
        'level_csv',
        'game_csv',
        'product_csv',
        'max_per_guest',
        'max_per_user',
        'max_global',
        'max_per_daily',
        'valid_from',
        'valid_until',
        'status',
    ];

    public function getActiveByCode(string $code): array
    {
        $now = date('Y-m-d H:i:s');

        $data = $this->where('code', $code)
            ->where('status', 'On')
            ->groupStart()
                ->where('valid_from IS NULL', null, false)
                ->orWhere('valid_from <=', $now)
            ->groupEnd()
            ->groupStart()
                ->where('valid_until IS NULL', null, false)
                ->orWhere('valid_until >=', $now)
            ->groupEnd()
            ->first();

        return $data ?? [];
    }

    /**
     * Admin listing — not restricted to status = 'On'.
     */
    public function paginatedList(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        $builder = $this->orderBy('id', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('code', $keyword)
                ->orLike('name', $keyword)
                ->groupEnd();
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        $items = $builder->paginate($perPage);

        return [
            'items' => $items,
            'pager' => $this->pager,
        ];
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $builder = $this->where('code', $code);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Atomically increments usage_count, guarded by max_global (NULL = unlimited).
     * Mirrors FlashsaleItemModel::consumeStock — the SQL WHERE clause is the
     * source of truth, not a prior SELECT, so concurrent requests can't both
     * succeed past a global cap.
     */
    public function consumeUsage(int $id): bool
    {
        $this->builder()
            ->set('usage_count', 'usage_count + 1', false)
            ->where('id', $id)
            ->where('status', 'On')
            ->groupStart()
                ->where('max_global IS NULL', null, false)
                ->orWhere('usage_count < max_global', null, false)
            ->groupEnd()
            ->update();

        return $this->db->affectedRows() > 0;
    }
}
