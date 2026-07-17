<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\HomePageOrchestrator;

class Home extends BaseController
{
    public function index()
    {
        $this->baseData['page_assets']['css'][] = 'resources/css/pages/home.css';
        $this->baseData['page_assets']['js'][]  = 'resources/js/pages/home.js';

        return $this->renderView('Pages/Home', $this->homePageOrchestrator()->getHomeData());
    }

    protected function homePageOrchestrator(): HomePageOrchestrator
    {
        return single_service('homePageOrchestrator');
    }
}
