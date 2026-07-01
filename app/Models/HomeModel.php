<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{


    public function getBanners(): array
    {
        return $this->db->table('banner')
            ->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getFlashsale(): array
    {
        return $this->db->table('flashsale')
        ->where('status', 'On')
        ->orderBy('id', 'DESC')
        ->get()
        ->getFirstRow('array') ?? [];
    }

    public function getFlashsaleProducts(int $flashsaleId): array
    {
        return $this->db->table('flashsale_items fi')
            ->select("
                fi.*,

                p.id,
                p.product,
                p.provider,
                p.price,
                p.raw_price,

                g.id AS game_id,
                g.games AS game_name,
                g.slug,
                g.image AS game_image,
                g.banner,

                CASE
                    WHEN fi.discount_type = 'fixed'
                        THEN p.price - fi.discount_value
                    ELSE
                        p.price - (p.price * fi.discount_value / 100)
                END AS sale_price
            ")
            ->join('product p', 'p.id = fi.product_id')
            ->join('games g', 'g.id = p.games_id')
            ->where('fi.flashsale_id', $flashsaleId)
            ->orderBy('fi.sort_order', 'ASC')
            ->get()
            ->getResultArray();
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
}
