<?php

namespace App\Services\Orchestrators\Storefront;

use App\Services\BaseService;
use App\Services\Marketing\BannerService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\GameCategoryService;

class HomePageOrchestrator extends BaseService
{
    protected $bannerService;
    protected $flashsaleService;
    protected $gameService;
    protected $gameCategoryService;

    public function __construct()
    {
        $this->bannerService        = new BannerService();
        $this->flashsaleService     = new FlashsaleService();
        $this->gameService          = new GameService();
        $this->gameCategoryService  = new GameCategoryService();
    }

    public function getHomeData(): array
    {
        return $this->safeCall(function () {
            return [
                'banners'           => $this->bannerService->getActive(),
                'flashsale'         => $this->flashsaleService->getHomeDisplay(),
                'popular_games'     => $this->gameService->getPopularGames(12),
                'category_sections' => $this->getCategorySections(),
            ];
        }, [
            'banners'           => [],
            'flashsale'         => [],
            'popular_games'     => [],
            'category_sections' => [],
        ]);
    }

    protected function getCategorySections(): array
    {
        $categories = $this->gameCategoryService->getActive();
        $sections   = [];

        foreach ($categories as $category) {
            $games = $this->gameService->getGamesByCategory((int) $category['id']);
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
}
