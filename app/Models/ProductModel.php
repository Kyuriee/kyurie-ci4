<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'product';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'games_id',
        'product',
        'sku',
        'provider',
        'raw_price',
        'price',
        'sort',
        'status',
    ];

    public function getProductsByGame(int $gameId): array
    {
        return $this->select('id, product, price')
            ->where('games_id', $gameId)
            ->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->findAll();
    }

    public function getDetailProduct(int $id): array
    {
        $data = $this->select('product.*, games.games, games.slug')
            ->join('games', 'games.id = product.games_id', 'left')
            ->where('product.id', $id)
            ->where('product.status', 'On')
            ->first();

        return $data ?? [];
    }

    /**
     * Admin listing — not restricted to status = 'On'.
     */
    public function paginatedList(string $keyword = '', ?int $gameId = null, string $status = '', int $perPage = 20): array
    {
        $builder = $this->select('product.*, games.games, games.slug')
            ->join('games', 'games.id = product.games_id', 'left')
            ->orderBy('product.sort', 'ASC')
            ->orderBy('product.id', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('product.product', $keyword)
                ->orLike('product.sku', $keyword)
                ->groupEnd();
        }

        if ($gameId !== null && $gameId > 0) {
            $builder->where('product.games_id', $gameId);
        }

        if ($status !== '') {
            $builder->where('product.status', $status);
        }

        $items = $builder->paginate($perPage);

        return [
            'items' => $items,
            'pager' => $this->pager,
        ];
    }
}
