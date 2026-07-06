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

        if (empty($products)) {
            return [];
        }

        // Ambil semua flashsale item aktif dalam 1 query (bukan query per produk
        // di dalam loop) biar gak N+1 kalau produk per game jumlahnya banyak.
        $productIds     = array_map(static fn (array $p) => (int) $p['id'], $products);
        $flashsaleItems = $this->flashsaleService->getActiveItemsForProducts($productIds);

        foreach ($products as &$product) {
            $flashsaleItem = $flashsaleItems[(int) $product['id']] ?? null;
            $finalPrice    = $this->priceService->getFinalPrice($product, $flashsaleItem);

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