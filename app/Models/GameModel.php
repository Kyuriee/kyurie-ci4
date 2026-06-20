<?php

namespace App\Models;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table         = 'games';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'game_category_id',
        'games',
        'slug',
        'code',
        'provider',
        'image',
        'banner',
        'description',
        'target',
        'is_popular',
        'sort',
        'status',
    ];

    public function getPopularGames(int $limit = 12): array
    {
        return $this->select('games.*, game_categories.category')
            ->join('game_categories', 'game_categories.id = games.game_category_id', 'left')
            ->where('games.status', 'On')
            ->where('games.is_popular', 'Y')
            ->orderBy('games.sort', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    public function getGamesByCategory(int $categoryId): array
    {
        return $this->where('status', 'On')
            ->where('game_category_id', $categoryId)
            ->orderBy('sort', 'ASC')
            ->findAll();
    }

    public function getDetailBySlug(string $slug): array
    {
        $data = $this->select('games.*, game_categories.category')
            ->join('game_categories', 'game_categories.id = games.game_category_id', 'left')
            ->where('games.slug', $slug)
            ->where('games.status', 'On')
            ->first();

        return $data ?? [];
    }

    public function searchGames(string $keyword, int $limit = 8): array
    {
        return $this->select('games.id, games.games, games.slug, games.image, games.target, game_categories.category')
            ->join('game_categories', 'game_categories.id = games.game_category_id', 'left')
            ->where('games.status', 'On')
            ->groupStart()
                ->like('games.games', $keyword)
                ->orLike('games.code', $keyword)
                ->orLike('game_categories.category', $keyword)
            ->groupEnd()
            ->orderBy('games.is_popular', 'DESC')
            ->orderBy('games.sort', 'ASC')
            ->limit($limit)
            ->findAll();
    }
}
