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
        return $this->where('games_id', $gameId)
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
}