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

    public function incrementSold(int $id): void
    {
        $item = $this->find($id);

        if (empty($item)) {
            return;
        }

        $sold = (int) $item['sold'] + 1;
        $data = ['sold' => $sold];

        if ($sold >= (int) $item['stock']) {
            $data['status'] = 'Off';
        }

        $this->update($id, $data);
    }
}