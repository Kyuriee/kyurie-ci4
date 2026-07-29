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
        'publisher',
        'image',
        'banner',
        'description',
        'target',
        'input_custom',
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
        return $this->select('games.id, games.games, games.slug, games.image, games.publisher, games.target, game_categories.category')
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

    /**
     * Admin listing — unlike the storefront methods above, this is NOT
     * restricted to status = 'On' so admins can see/manage Off games too.
     */
    public function paginatedList(string $keyword = '', ?int $categoryId = null, string $status = '', int $perPage = 20): array
    {
        $builder = $this->select('games.*, game_categories.category')
            ->join('game_categories', 'game_categories.id = games.game_category_id', 'left')
            ->orderBy('games.sort', 'ASC')
            ->orderBy('games.id', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('games.games', $keyword)
                ->orLike('games.code', $keyword)
                ->orLike('games.slug', $keyword)
                ->groupEnd();
        }

        if ($categoryId !== null && $categoryId > 0) {
            $builder->where('games.game_category_id', $categoryId);
        }

        if ($status !== '') {
            $builder->where('games.status', $status);
        }

        $items = $builder->paginate($perPage);

        return [
            'items' => $items,
            'pager' => $this->pager,
        ];
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->where('slug', $slug);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}
