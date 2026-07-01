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
            return [];
        }
        $remaining = max(
            0,
            strtotime($flashsale['date_end']) - time()
        );
        $products = $this->homeModel->getFlashsaleProducts($flashsale['id']);
        $flashsale['remaining_seconds'] = $remaining;
        $flashsale['countdown'] = [
            'days'    => floor($remaining / 86400),
            'hours'   => floor(($remaining % 86400) / 3600),
            'minutes' => floor(($remaining % 3600) / 60),
            'seconds' => $remaining % 60,
        ];
        $flashsale['products'] = $products;
        $flashsale['progress'] = $this->calculateFlashsaleProgress($products);
        return $flashsale;
    }

    protected function calculateFlashsaleProgress(array $items): int
    {
        $stock = array_sum(array_column($items, 'stock'));
        $sold  = array_sum(array_column($items, 'sold'));
        if ($stock <= 0) {
            return 0;
        }
        return (int) round(($sold / $stock) * 100);
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
