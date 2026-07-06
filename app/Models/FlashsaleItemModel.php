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

    /**
     * Versi batch dari getActiveForProduct(), 1 query buat banyak product_id
     * sekaligus (biar gak N+1 pas dipanggil dari daftar produk per game).
     * Return keyed by product_id.
     */
    public function getActiveForProducts(array $productIds): array
    {
        $productIds = array_values(array_unique(array_filter(
            array_map('intval', $productIds),
            static fn ($id) => $id > 0
        )));

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
            ->findAll();

        $keyed = [];

        foreach ($rows as $row) {
            $keyed[(int) $row['product_id']] = $row;
        }

        return $keyed;
    }

    /**
     * Nambah `sold` secara atomic pake UPDATE ... WHERE sold < stock,
     * bukan read-then-write (find() lalu update()) kayak sebelumnya.
     *
     * Kenapa penting: pattern lama rawan race condition — dua request
     * konkuren yang barengan confirm order bisa sama-sama baca `sold` yang
     * sama sebelum salah satunya sempet nulis, hasilnya oversold (sold
     * kelewat dari stock). Dengan satu UPDATE atomic yang row-locked di
     * level DB, cuma satu request yang bakal berhasil kalau stok tinggal 1.
     *
     * @return bool true kalau increment berhasil (masih ada stok), false kalau
     *              item gak ketemu atau stok emang udah habis.
     */
    public function incrementSold(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $builder = $this->builder();

        $builder->set('sold', 'sold + 1', false)
            ->where('id', $id)
            ->where('sold < stock', null, false);

        $builder->update();

        $affected = $this->db->affectedRows();

        if ($affected <= 0) {
            return false;
        }

        $item = $this->select('sold, stock')->find($id);

        if ($item && (int) $item['sold'] >= (int) $item['stock']) {
            $this->update($id, ['status' => 'Off']);
        }

        return true;
    }
}