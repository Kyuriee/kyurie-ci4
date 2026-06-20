<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoreSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        /*
         * Admin
         * Login:
         * username: admin
         * password: admin123
         */
        $this->db->table('admin')->insert([
            'username'   => 'admin',
            'email'      => 'admin@kyurie.test',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'name'       => 'Super Admin',
            'level'      => 'Superadmin',
            'status'     => 'On',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
         * User
         * Login:
         * username: member
         * password: member123
         */
        $this->db->table('users')->insert([
            'username'   => 'member',
            'email'      => 'member@kyurie.test',
            'password'   => password_hash('member123', PASSWORD_DEFAULT),
            'phone'      => '6281234567890',
            'balance'    => 100000,
            'level'      => 'Member',
            'status'     => 'On',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
         * Banner
         */
        $this->db->table('banner')->insertBatch([
            [
                'title'      => 'Top Up Game Cepat dan Aman',
                'subtitle'   => 'Nikmati proses top up otomatis, harga murah, dan layanan terpercaya setiap hari.',
                'image'      => 'banner-1.png',
                'link'       => '#products',
                'sort'       => 1,
                'status'     => 'On',
                'date_start' => null,
                'date_end'   => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'Flash Sale Spesial',
                'subtitle'   => 'Dapatkan promo terbatas untuk produk pilihan.',
                'image'      => 'banner-2.png',
                'link'       => '#flashsale',
                'sort'       => 2,
                'status'     => 'On',
                'date_start' => null,
                'date_end'   => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
         * Flashsale
         */
        $this->db->table('flashsale')->insert([
            'title'       => 'Flash Sale Launching',
            'description' => 'Promo khusus untuk pembukaan website.',
            'image'       => 'flashsale.png',
            'date_start'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            'date_end'    => date('Y-m-d H:i:s', strtotime('+7 days')),
            'status'      => 'On',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $flashsale_id = $this->db->insertID();

        /*
         * Game Categories
         */
        $this->db->table('game_categories')->insertBatch([
            [
                'category'   => 'Populer',
                'slug'       => 'populer',
                'image'      => 'category-populer.png',
                'sort'       => 1,
                'status'     => 'On',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category'   => 'Mobile Games',
                'slug'       => 'mobile-games',
                'image'      => 'category-mobile-games.png',
                'sort'       => 2,
                'status'     => 'On',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category'   => 'Voucher',
                'slug'       => 'voucher',
                'image'      => 'category-voucher.png',
                'sort'       => 3,
                'status'     => 'On',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $category = $this->db->table('game_categories')
            ->where('slug', 'mobile-games')
            ->get()
            ->getRowArray();

        $category_id = $category['id'] ?? null;

        /*
         * Games
         */
        $this->db->table('games')->insertBatch([
            [
                'game_category_id' => $category_id,
                'games'            => 'Mobile Legends',
                'slug'             => 'mobile-legends',
                'code'             => 'MLBB',
                'provider'         => 'Manual',
                'image'            => 'mobile-legends.png',
                'banner'           => 'mobile-legends-banner.png',
                'description'      => 'Top up Mobile Legends cepat dan aman.',
                'target'           => 'zone',
                'is_popular'       => 'Y',
                'sort'             => 1,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'game_category_id' => $category_id,
                'games'            => 'Free Fire',
                'slug'             => 'free-fire',
                'code'             => 'FF',
                'provider'         => 'Manual',
                'image'            => 'free-fire.png',
                'banner'           => 'free-fire-banner.png',
                'description'      => 'Top up Free Fire murah dan terpercaya.',
                'target'           => 'default',
                'is_popular'       => 'Y',
                'sort'             => 2,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'game_category_id' => $category_id,
                'games'            => 'Genshin Impact',
                'slug'             => 'genshin-impact',
                'code'             => 'GI',
                'provider'         => 'Manual',
                'image'            => 'genshin-impact.png',
                'banner'           => 'genshin-impact-banner.png',
                'description'      => 'Top up Genshin Impact dengan proses mudah.',
                'target'           => 'server',
                'is_popular'       => 'N',
                'sort'             => 3,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        $mlbb = $this->db->table('games')
            ->where('slug', 'mobile-legends')
            ->get()
            ->getRowArray();

        $ff = $this->db->table('games')
            ->where('slug', 'free-fire')
            ->get()
            ->getRowArray();

        /*
         * Product
         */
        $this->db->table('product')->insertBatch([
            [
                'games_id'         => $mlbb['id'],
                'flashsale_id'     => $flashsale_id,
                'product'          => '86 Diamonds',
                'sku'              => 'MLBB-86',
                'provider'         => 'Manual',
                'raw_price'        => 19000,
                'price'            => 21000,
                'discount_price'   => 20000,
                'flashsale_price'  => 18500,
                'stock'            => 999,
                'sold'             => 0,
                'sort'             => 1,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'games_id'         => $mlbb['id'],
                'flashsale_id'     => null,
                'product'          => '172 Diamonds',
                'sku'              => 'MLBB-172',
                'provider'         => 'Manual',
                'raw_price'        => 38000,
                'price'            => 41000,
                'discount_price'   => 0,
                'flashsale_price'  => 0,
                'stock'            => 999,
                'sold'             => 0,
                'sort'             => 2,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'games_id'         => $ff['id'],
                'flashsale_id'     => $flashsale_id,
                'product'          => '70 Diamonds',
                'sku'              => 'FF-70',
                'provider'         => 'Manual',
                'raw_price'        => 9000,
                'price'            => 11000,
                'discount_price'   => 10000,
                'flashsale_price'  => 8500,
                'stock'            => 999,
                'sold'             => 0,
                'sort'             => 1,
                'status'           => 'On',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);
    }
}