<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('utilities')->insertBatch([
            [
                'u_key'       => 'web_name',
                'u_value'     => 'Kyrie Store',
                'type'        => 'text',
                'description' => 'Nama website',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'web_title',
                'u_value'     => 'Kyrie Store - Top Up Game Cepat dan Aman',
                'type'        => 'text',
                'description' => 'Default meta title',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'web_description',
                'u_value'     => 'Tempat top up game cepat, aman, dan terpercaya.',
                'type'        => 'textarea',
                'description' => 'Default meta description',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'web_logo',
                'u_value'     => 'logo.png',
                'type'        => 'image',
                'description' => 'Logo website',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'web_favicon',
                'u_value'     => 'favicon.png',
                'type'        => 'image',
                'description' => 'Favicon website',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'maintenance',
                'u_value'     => 'Off',
                'type'        => 'select',
                'description' => 'Status maintenance website',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'currency',
                'u_value'     => 'IDR',
                'type'        => 'text',
                'description' => 'Default currency',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'u_key'       => 'whatsapp_admin',
                'u_value'     => '6281234567890',
                'type'        => 'text',
                'description' => 'Nomor WhatsApp admin',
                'is_public'   => 'Y',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        $this->db->table('credentials')->insertBatch([
            [
                'provider'    => 'tripay',
                'c_key'       => 'merchant_code',
                'c_value'     => '',
                'type'        => 'text',
                'mode'        => 'production',
                'status'      => 'Off',
                'description' => 'Tripay merchant code',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'provider'    => 'tripay',
                'c_key'       => 'api_key',
                'c_value'     => '',
                'type'        => 'password',
                'mode'        => 'production',
                'status'      => 'Off',
                'description' => 'Tripay API key',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'provider'    => 'tripay',
                'c_key'       => 'private_key',
                'c_value'     => '',
                'type'        => 'password',
                'mode'        => 'production',
                'status'      => 'Off',
                'description' => 'Tripay private key',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}