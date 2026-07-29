<?php

namespace App\Models;

use CodeIgniter\Model;

class FlashsaleItemModel extends Model
{
    protected $table         = 'flashsale_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'flashsale_id',
        'product_id',
        'stock',
        'sold',
        'discount_type',
        'discount_value',
        'sort_order',
        'status',
    ];

    public function getActiveForProduct(int $productId): array
    {
        $now = date('Y-m-d H:i:s');

        $data = $this->select('flashsale_items.*, flashsale.date_start, flashsale.date_end')
            ->join('flashsale', 'flashsale.id = flashsale_items.flashsale_id')
            ->where('flashsale_items.product_id', $productId)
            ->where('flashsale_items.status', 'On')
            ->where('flashsale.status', 'On')
            ->where('flashsale.date_start <=', $now)
            ->where('flashsale.date_end >=', $now)
            ->first();

        return $data ?? [];
    }

    public function getActiveForProducts(array $productIds): array
    {
        $productIds = array_values(array_unique(array_filter(array_map('intval', $productIds))));

        if (empty($productIds)) {
            return [];
        }

        $now = date('Y-m-d H:i:s');

        $rows = $this->select('flashsale_items.*, flashsale.date_start, flashsale.date_end')
            ->join('flashsale', 'flashsale.id = flashsale_items.flashsale_id')
            ->whereIn('flashsale_items.product_id', $productIds)
            ->where('flashsale_items.status', 'On')
            ->where('flashsale.status', 'On')
            ->where('flashsale.date_start <=', $now)
            ->where('flashsale.date_end >=', $now)
            ->orderBy('flashsale_items.product_id', 'ASC')
            ->orderBy('flashsale_items.sort_order', 'ASC')
            ->findAll();

        $items = [];

        foreach ($rows as $row) {
            $productId = (int) ($row['product_id'] ?? 0);

            if ($productId > 0 && ! isset($items[$productId])) {
                $items[$productId] = $row;
            }
        }

        return $items;
    }

    public function getHomeProductsByFlashsale(int $flashsaleId): array
    {
        return $this->db->table('flashsale_items fi')
            ->select("
                fi.*,

                p.id,
                p.product,
                p.provider,
                p.price,
                p.raw_price,

                g.id AS game_id,
                g.games AS game_name,
                g.slug,
                g.image AS game_image,
                g.banner,

                CASE
                    WHEN fi.discount_type = 'fixed'
                        THEN p.price - fi.discount_value
                    ELSE
                        p.price - (p.price * fi.discount_value / 100)
                END AS sale_price
            ")
            ->join('product p', 'p.id = fi.product_id')
            ->join('games g', 'g.id = p.games_id')
            ->where('fi.flashsale_id', $flashsaleId)
            ->orderBy('fi.sort_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function consumeStock(int $id): bool
    {
        $this->builder()
            ->set('status', "CASE WHEN sold + 1 >= stock THEN 'Off' ELSE status END", false)
            ->set('sold', 'sold + 1', false)
            ->where('id', $id)
            ->where('status', 'On')
            ->where('sold < stock', null, false)
            ->update();

        return $this->db->affectedRows() > 0;
    }

    public function incrementSold(int $id): void
    {
        $this->consumeStock($id);
    }

    /**
     * Admin listing of items within one flashsale, joined with product/game
     * for display context.
     */
    public function paginatedListByFlashsale(int $flashsaleId, int $perPage = 20): array
    {
        $items = $this->select('flashsale_items.*, product.product, product.price, games.games')
            ->join('product', 'product.id = flashsale_items.product_id', 'left')
            ->join('games', 'games.id = product.games_id', 'left')
            ->where('flashsale_items.flashsale_id', $flashsaleId)
            ->orderBy('flashsale_items.sort_order', 'ASC')
            ->orderBy('flashsale_items.id', 'DESC')
            ->paginate($perPage);

        return [
            'items' => $items,
            'pager' => $this->pager,
        ];
    }
}
