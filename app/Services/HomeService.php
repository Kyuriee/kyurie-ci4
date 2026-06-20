<?php

namespace App\Services;

use App\Models\HomeModel;

class HomeService extends BaseService
{
    protected $homeModel;
    protected $priceService;

    public function __construct()
    {
        $this->homeModel     = model(HomeModel::class);
        $this->priceService  = new PriceService();
    }
    
    public function getBanners(): array
    {
        return $this->homeModel->getBanners();
    }

    public function getFlashsale(): array
    {
        $flashsale = $this->homeModel->getFlashsale();

        if (empty($flashsale)) {
            return [
                'data'     => [],
                'products' => [],
            ];
        }

        $products = $this->homeModel->getFlashsaleProducts(8);
        $products = $this->formatProducts($products);

        return [
            'data'     => $flashsale,
            'products' => $products,
        ];
    }

    public function getPopularGames(): array
    {
        return $this->homeModel->getPopularGames(12);
    }

    public function getCategorySections(): array
    {
        $categories = $this->homeModel->getActiveCategories();
        $sections   = [];

        foreach ($categories as $category) {
            $games = $this->homeModel->getGamesByCategory((int) $category['id']);

            if (empty($games)) {
                continue;
            }

            $sections[] = [
                'category' => $category,
                'games'    => $games,
            ];
        }

        return $sections;
    }

    protected function formatProducts(array $products): array
    {
        foreach ($products as &$product) {
            $final_price = $this->priceService->getFinalPrice($product);

            $product['final_price']            = $final_price;
            $product['final_price_formatted']  = $this->priceService->formatPrice($final_price);
            $product['normal_price_formatted'] = $this->priceService->formatPrice((float) ($product['price'] ?? 0));
        }

        return $products;
    }
}
