<?php

namespace App\Models;

use CodeIgniter\Model;

class FlashsaleModel extends Model
{
    protected $table         = 'flashsale';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'title',
        'description',
        'image',
        'date_start',
        'date_end',
        'status',
    ];

    public function getActive(): array
    {
        return $this->where('status', 'On')
            ->orderBy('id', 'DESC')
            ->first() ?? [];
    }

    /**
     * Admin listing — not restricted to status = 'On'.
     */
    public function paginatedList(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        $builder = $this->orderBy('id', 'DESC');

        if ($keyword !== '') {
            $builder->like('title', $keyword);
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
