<?php

namespace App\Services;

use App\Models\ProductModel;

class ProductService extends baseService
{
    protected $productModel;
    protected $flashsaleService;
    protected $priceService;

    public function __construct()
    {
        $this->productModel     = model(ProductModel::class);
        $this->flashsaleService = new FlashsaleService();
        $this->priceService     = new PriceService();
    }

    public function getDetail(int $productId): array
    {
        if ($productId <= 0) {
            return [];
        }

        return $this->productModel->getDetailProduct($productId);
    }

    public function getPublicProductsByGame(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        $products = $this->productModel->getProductsByGame($gameId);

        foreach ($products as &$product) {
            $flashsaleItem = $this->flashsaleService->getActiveItemForProduct((int) $product['id']);
            $finalPrice    = $this->priceService->getFinalPrice($product, $flashsaleItem ?: null);

            $product = $this->mapPublicProduct($product, $finalPrice, ! empty($flashsaleItem));
        }
        unset($product);

        return $products;
    }

    protected function mapPublicProduct(array $product, float $finalPrice, bool $isFlashsale): array
    {
        return [
            'id'                    => (int) $product['id'],
            'product'               => $product['product'],
            'is_flashsale'          => $isFlashsale,
            'final_price_formatted' => $this->priceService->formatPrice($finalPrice),
            'price_formatted'       => $this->priceService->formatPrice((float) ($product['price'] ?? 0)),
        ];
    }
}
