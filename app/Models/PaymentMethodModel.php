<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table         = 'payment_methods';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $protectFields = false;

    public function getActive(): array
    {
        return $this->select('id, name, image')
            ->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->findAll();
    }

    public function find($id = null): array
    {
        $data = parent::find($id);

        return $data ?? [];
    }

    /**
     * Admin listing — not restricted to status = 'On'.
     */
    public function paginatedList(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        $builder = $this->orderBy('sort', 'ASC')->orderBy('id', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('provider', $keyword)
                ->orLike('code', $keyword)
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
}
