<?php

namespace App\Services\Catalog;

use App\Models\ProductModel;
use App\Services\BaseService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Pricing\PriceService;

class ProductService extends BaseService
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

        return $this->safeCall(
            fn() => $this->productModel->getDetailProduct($productId),
            []
        );
    }

    public function getPublicProductsByGame(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        return $this->safeCall(function () use ($gameId) {
            $products       = $this->productModel->getProductsByGame($gameId);
            $flashsaleItems = $this->flashsaleService->getActiveItemsForProducts(array_column($products, 'id'));

            foreach ($products as &$product) {
                $flashsaleItem = $flashsaleItems[(int) $product['id']] ?? [];
                $finalPrice    = $this->priceService->getFinalPrice($product, $flashsaleItem ?: null);
                $product       = $this->mapPublicProduct($product, $finalPrice, ! empty($flashsaleItem));
            }
            unset($product);

            return $products;
        }, []);
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
