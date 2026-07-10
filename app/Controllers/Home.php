<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\HomePageOrchestrator;

class Home extends BaseController
{
    public function index()
    {
        $this->base_data['page_assets']['css'][] = 'resources/css/pages/home.css';
        $this->base_data['page_assets']['js'][]  = 'resources/js/pages/home.js';

        return $this->renderView('Pages/Home', $this->_service()->getHomeData());
    }

    protected function _service(): HomePageOrchestrator
    {
        return single_service('homePageOrchestrator');
    }
}
