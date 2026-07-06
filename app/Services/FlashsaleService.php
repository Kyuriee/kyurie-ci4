<?php

namespace App\Services;

use App\Models\FlashsaleItemModel;

class FlashsaleService extends baseService
{
    protected $flashsaleItemModel;

    public function __construct()
    {
        $this->flashsaleItemModel = model(FlashsaleItemModel::class);
    }

    public function getActiveItemForProduct(int $productId): array
    {
        if ($productId <= 0) {
            return [];
        }

        return $this->flashsaleItemModel->getActiveForProduct($productId);
    }

    /**
     * Batch version — ambil flashsale aktif buat sekumpulan product_id
     * dalam 1 query, keyed by product_id. Dipake ProductService biar gak N+1.
     */
    public function getActiveItemsForProducts(array $productIds): array
    {
        return $this->flashsaleItemModel->getActiveForProducts($productIds);
    }

    public function hasAvailableStock(array $flashsaleItem): bool
    {
        if (empty($flashsaleItem)) {
            return true;
        }

        return (int) ($flashsaleItem['sold'] ?? 0) < (int) ($flashsaleItem['stock'] ?? 0);
    }

    /**
     * @return bool true kalau berhasil nambah sold (stok masih ada), false
     *              kalau item gak valid atau stoknya emang udah abis duluan.
     *              Caller (misal OrderService) sebaiknya cek return value ini,
     *              bukan cuma asumsi selalu berhasil kayak sebelumnya.
     */
    public function incrementSold(int $flashsaleItemId): bool
    {
        if ($flashsaleItemId <= 0) {
            return false;
        }

        return $this->flashsaleItemModel->incrementSold($flashsaleItemId);
    }
}