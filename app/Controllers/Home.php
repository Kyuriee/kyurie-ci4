<?php

namespace App\Controllers;

use App\Services\HomeService;

class Home extends BaseController
{
    public function index()
    {
        $service   = $this->_service();
        
        $data = [
            'banners'            => $service->getBanners(),
            'flashsale'          => $service->getFlashsale(),
            'popular_games'      => $service->getPopularGames(12),
            'category_sections'  => $service->getCategorySections(),
        ];
        $this->base_data['page_assets']['css'][] = 'resources/css/pages/home.css';
        $this->base_data['page_assets']['js'][] = 'resources/js/pages/home.js';
       
        return $this->renderView('Pages/Home', $data);
    }

    protected function _service(): HomeService
    {
        return single_service('homeService');
    }
}