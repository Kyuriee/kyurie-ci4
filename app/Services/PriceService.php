<?php

namespace App\Services;

class PriceService extends baseService
{
    public function calculateSellingPrice(float $rawPrice, float $marginPercent = 0, float $marginNominal = 0): float
    {
        return ceil(($rawPrice * (100 + $marginPercent) / 100) + $marginNominal);
    }

    public function getFinalPrice(array $product, ?array $flashsaleItem = null): float
    {
        $price = (float) ($product['price'] ?? 0);

        if (empty($flashsaleItem)) {
            return $price;
        }

        if ($flashsaleItem['discount_type'] === 'percent') {
            return max(0, $price - ($price * (float) $flashsaleItem['discount_value'] / 100));
        }

        return max(0, $price - (float) $flashsaleItem['discount_value']);
    }

    public function formatPrice(float $price): string
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}