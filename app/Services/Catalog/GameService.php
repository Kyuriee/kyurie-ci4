<?php

namespace App\Services\Catalog;

use App\Services\baseService;
use App\Models\GameModel;

class GameService extends baseService
{
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = model(GameModel::class);
    }

    public function getActiveBySlug(string $slug): array
    {
        $slug = trim($slug);

        if ($slug === '') {
            return [];
        }

        return $this->gameModel->getDetailBySlug($slug);
    }

    public function getById(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        return $this->gameModel->find($gameId) ?: [];
    }

    public function searchGames(string $keyword, int $limit = 8): array
    {
        $keyword = trim($keyword);

        if (strlen($keyword) < 2) {
            return [];
        }

        $games = $this->gameModel->searchGames($keyword, $limit);

        foreach ($games as &$game) {
            $game['url']       = base_url('games/' . $game['slug']);
            $game['image_url'] = ! empty($game['image'])
                ? base_url('assets/images/games/icons/' . $game['image'])
                : '';
        }

        return $games;
    }

    public function getPopularGames(int $limit = 12): array
    {
        return $this->safeCall(
            fn() => $this->gameModel->getPopularGames($limit),
            []
        );
    }

    public function getGamesByCategory(int $categoryId): array
    {
        if ($categoryId <= 0) {
            return [];
        }

        return $this->safeCall(
            fn() => $this->gameModel->getGamesByCategory($categoryId),
            []
        );
    }

    public function mapPublicGame(array $game): array
    {
        return [
            'games'     => $game['games'] ?? '',
            'slug'      => $game['slug'] ?? '',
            'publisher' => $game['publisher'] ?? '',
            'category'  => $game['category'] ?? '',
            'image'     => $game['image'] ?? '',
            'banner'    => $game['banner'] ?? '',
            'target'    => $game['target'] ?? 'default',
        ];
    }
}
