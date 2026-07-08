<?php

namespace App\Services\Marketing;

use App\Services\baseService;
use App\Models\FlashsaleItemModel;
use App\Models\FlashsaleModel;

class FlashsaleService extends baseService
{
    protected $flashsaleItemModel;
    protected $flashsaleModel;

    public function __construct()
    {
        $this->flashsaleItemModel = model(FlashsaleItemModel::class);
        $this->flashsaleModel     = model(FlashsaleModel::class);
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

    public function getHomeDisplay(): array
    {
        return $this->safeCall(function () {
            $flashsale = $this->flashsaleModel->getActive();

            if (empty($flashsale)) {
                return [];
            }

            $remaining = max(
                0,
                strtotime($flashsale['date_end']) - time()
            );
            $products = $this->flashsaleItemModel->getHomeProductsByFlashsale((int) $flashsale['id']);

            $flashsale['remaining_seconds'] = $remaining;
            $flashsale['countdown'] = [
                'days'    => floor($remaining / 86400),
                'hours'   => floor(($remaining % 86400) / 3600),
                'minutes' => floor(($remaining % 3600) / 60),
                'seconds' => $remaining % 60,
            ];
            $flashsale['products'] = $products;
            $flashsale['progress'] = $this->calculateHomeProgress($products);

            return $flashsale;
        }, []);
    }

    protected function calculateHomeProgress(array $items): int
    {
        $stock = array_sum(array_column($items, 'stock'));
        $sold  = array_sum(array_column($items, 'sold'));

        if ($stock <= 0) {
            return 0;
        }

        return (int) round(($sold / $stock) * 100);
    }
}
