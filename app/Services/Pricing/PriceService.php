<?php

namespace App\Services\Pricing;

use App\Services\BaseService;

class PriceService extends BaseService
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

        $discountType  = $flashsaleItem['discount_type'] ?? '';
        $discountValue = (float) ($flashsaleItem['discount_value'] ?? 0);

        if ($discountType === 'percent') {
            return max(0, $price - ($price * $discountValue / 100));
        }

        return max(0, $price - $discountValue);
    }

    public function formatPrice(float $price): string
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
