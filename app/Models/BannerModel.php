<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table         = 'banner';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'title',
        'subtitle',
        'image',
        'link',
        'sort',
        'status',
        'date_start',
        'date_end',
    ];

    public function getActive(): array
    {
        return $this->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->findAll();
    }

    /**
     * Admin listing — not restricted to status = 'On'.
     */
    public function paginatedList(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        $builder = $this->orderBy('sort', 'ASC')->orderBy('id', 'DESC');

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
