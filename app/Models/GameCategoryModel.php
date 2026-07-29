<?php

namespace App\Models;

use CodeIgniter\Model;

class GameCategoryModel extends Model
{
    protected $table         = 'game_categories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'category',
        'slug',
        'image',
        'sort',
        'status',
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
            $builder->groupStart()
                ->like('category', $keyword)
                ->orLike('slug', $keyword)
                ->groupEnd();
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        $items = $builder->paginate($perPage);

        return [
            'items' => $items,
            // ponytail: $this->pager is a Pager object with no public props,
            // so json_encode() on it silently gave "{}" — pull the flat
            // details array instead so the admin UI can actually paginate.
            'pager' => [
                'currentPage' => $this->pager->getCurrentPage(),
                'perPage'     => $this->pager->getPerPage(),
                'pageCount'   => $this->pager->getPageCount(),
                'total'       => $this->pager->getTotal(),
            ],
        ];
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->where('slug', $slug);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}
