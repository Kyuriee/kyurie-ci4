<?php

namespace App\Services;

class PriceService extends baseService
{
    public function calculateSellingPrice(float $rawPrice, float $marginPercent = 0, float $marginNominal = 0): float
    {
        return ceil(($rawPrice * (100 + $marginPercent) / 100) + $marginNominal);
    }

    public function getFinalPrice(array $product): float
    {
        if (! empty($product['flashsale_price']) && (float) $product['flashsale_price'] > 0) {
            return (float) $product['flashsale_price'];
        }

        if (! empty($product['discount_price']) && (float) $product['discount_price'] > 0) {
            return (float) $product['discount_price'];
        }

        return (float) ($product['price'] ?? 0);
    }

    public function formatPrice(float $price): string
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
