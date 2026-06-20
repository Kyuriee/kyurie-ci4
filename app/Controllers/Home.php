<?php

namespace App\Controllers;

use App\Services\HomeService;

class Home extends BaseController
{
    public function index()
    {
        $service   = $this->_service();
        $banners   = $service->getBanners();
        $flashsale = $service->getFlashsale();

        $data = [
            'banners'            => $banners,
            'hero_banner'        => $banners[0] ?? [],
            'flashsale'          => $flashsale['data'] ?? [],
            'flashsale_products' => $flashsale['products'] ?? [],
            'popular_games'      => $service->getPopularGames(12),
            'category_sections'  => $service->getCategorySections(),
        ];

        return $this->renderView('Home/Index', $data);
    }

    protected function _service(): HomeService
    {
        return single_service('homeService');
    }
}