<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ImageAssetSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $banners = $this->db->table('banner')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($banners as $index => $banner) {
            $image = $index === 0 ? 'banner-1.jpg' : 'banner-2.jpg';

            $this->db->table('banner')
                ->where('id', $banner['id'])
                ->update([
                    'image'      => $image,
                    'updated_at' => $now,
                ]);
        }

        $this->db->table('games')
            ->update([
                'image'      => 'game-default.jpg',
                'banner'     => 'game-default.jpg',
                'updated_at' => $now,
            ]);
    }
}