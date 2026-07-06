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

    public function consumeStock(int $flashsaleItemId): bool
    {
        if ($flashsaleItemId <= 0) {
            return false;
        }

        return $this->flashsaleItemModel->consumeStock($flashsaleItemId);
    }

    public function incrementSold(int $flashsaleItemId): void
    {
        $this->consumeStock($flashsaleItemId);
    }
}
