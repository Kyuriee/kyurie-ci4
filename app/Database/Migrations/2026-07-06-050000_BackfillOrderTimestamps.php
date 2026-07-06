<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Data-fix buat order yang ke-insert sebelum OrderModel punya
 * $useTimestamps = true, jadi created_at/updated_at-nya ke-skip
 * (NULL) walau kolomnya udah ada dari migration awal.
 *
 * Ini migration data, bukan schema — down() sengaja no-op karena
 * gak ada cara aman buat tau baris mana yang backfill vs yang emang
 * asli NULL dari awal.
 */
class BackfillOrderTimestamps extends Migration
{
    public function up()
    {
        $this->db->table('orders')
            ->where('created_at', null)
            ->update([
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function down()
    {
        // Sengaja no-op — data-fix satu arah, gak reversible dengan aman.
    }
}
