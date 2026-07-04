<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupProductColumns extends Migration
{
    protected array $columns = [
        'flashsale_id',
        'discount_price',
        'flashsale_price',
        'stock',
        'sold',
    ];

    public function up()
    {
        // FK 'flashsale_id' harus dilepas dulu sebelum kolomnya di-drop
        if ($this->db->fieldExists('flashsale_id', 'product')) {
            try {
                $this->forge->dropForeignKey('product', 'product_flashsale_id_foreign');
            } catch (\Throwable $e) {
                // nama constraint beda / udah gak ada, aman diabaikan
            }
        }

        foreach ($this->columns as $column) {
            if ($this->db->fieldExists($column, 'product')) {
                $this->forge->dropColumn('product', $column);
            }
        }
    }

    public function down()
    {
        // Arsitektur udah pindah ke flashsale_items, sengaja gak dikembaliin
    }
}