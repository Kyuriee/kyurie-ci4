<?php

namespace App\Services;

class HomeService extends baseService
{
    protected $bannerService;
    protected $flashsaleService;
    protected $gameService;
    protected $categoryService;

    public function __construct()
    {
        $this->bannerService    = new BannerService();
        $this->flashsaleService = new FlashsaleService();
        $this->gameService      = new GameService();
        $this->categoryService  = new CategoryService();
    }

    public function getBanners(): array
    {
        return $this->bannerService->getActive();
    }

    public function getFlashsale(): array
    {
        return $this->flashsaleService->getHomeDisplay();
    }

    public function getPopularGames(int $limit = 12): array
    {
        return $this->gameService->getPopularGames($limit);
    }

    public function getCategorySections(): array
    {
        $categories = $this->categoryService->getActive();
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
