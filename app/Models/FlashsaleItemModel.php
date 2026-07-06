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
}
