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

    public function hasAvailableStock(array $flashsaleItem): bool
    {
        if (empty($flashsaleItem)) {
            return true;
        }

        return (int) ($flashsaleItem['sold'] ?? 0) < (int) ($flashsaleItem['stock'] ?? 0);
    }

    public function incrementSold(int $flashsaleItemId): void
    {
        if ($flashsaleItemId <= 0) {
            return;
        }

        $this->flashsaleItemModel->incrementSold($flashsaleItemId);
    }
}
