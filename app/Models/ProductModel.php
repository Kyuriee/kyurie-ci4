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
        'flashsale_id',
        'product',
        'sku',
        'provider',
        'raw_price',
        'price',
        'discount_price',
        'flashsale_price',
        'stock',
        'sold',
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

    public function getFlashsaleProducts(int $limit = 12): array
    {
        $now = date('Y-m-d H:i:s');

        return $this->select('product.*, games.games, games.slug, games.image, flashsale.title as flashsale_title, flashsale.date_end')
            ->join('games', 'games.id = product.games_id', 'left')
            ->join('flashsale', 'flashsale.id = product.flashsale_id', 'left')
            ->where('product.status', 'On')
            ->where('games.status', 'On')
            ->where('flashsale.status', 'On')
            ->where('flashsale.date_start <=', $now)
            ->where('flashsale.date_end >=', $now)
            ->where('product.flashsale_price >', 0)
            ->orderBy('product.sort', 'ASC')
            ->limit($limit)
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
