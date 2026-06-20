<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{


    public function getBanners(): array
    {
        $now = date('Y-m-d H:i:s');

        return $this->db->table('banner')
            ->where('status', 'On')
            ->groupStart()
                ->where('date_start', null)
                ->orWhere('date_start <=', $now)
            ->groupEnd()
            ->groupStart()
                ->where('date_end', null)
                ->orWhere('date_end >=', $now)
            ->groupEnd()
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getFlashsale(): array
    {
        $now = date('Y-m-d H:i:s');

        $data = $this->db->table('flashsale')
            ->where('status', 'On')
            ->where('date_start <=', $now)
            ->where('date_end >=', $now)
            ->orderBy('id', 'DESC')
            ->get()
            ->getFirstRow('array');

        return $data ?? [];
    }

    public function getActiveCategories(): array
    {
        return $this->db->table('game_categories')
            ->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getPopularGames(int $limit = 12): array
    {
        return $this->db->table('games')
            ->select('games.*, game_categories.category')
            ->join('game_categories', 'game_categories.id = games.game_category_id', 'left')
            ->where('games.status', 'On')
            ->where('games.is_popular', 'Y')
            ->orderBy('games.sort', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getGamesByCategory(int $categoryId): array
    {
        return $this->db->table('games')
            ->where('status', 'On')
            ->where('game_category_id', $categoryId)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getFlashsaleProducts(int $limit = 8): array
    {
        $now = date('Y-m-d H:i:s');

        return $this->db->table('product')
            ->select('product.*, games.games, games.slug, games.image, flashsale.title as flashsale_title, flashsale.date_end')
            ->join('games', 'games.id = product.games_id', 'left')
            ->join('flashsale', 'flashsale.id = product.flashsale_id', 'left')
            ->where('product.status', 'On')
            ->where('games.status', 'On')
            ->where('flashsale.status', 'On')
            ->where('flashsale.date_start <=', $now)
            ->where('flashsale.date_end >=', $now)
            ->where('product.flashsale_price >', 0)
            ->orderBy('product.sort', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
