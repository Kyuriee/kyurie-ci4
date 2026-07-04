<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlashsaleItemIdToOrders extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('flashsale_item_id', 'orders')) {
            $this->forge->addColumn('orders', [
                'flashsale_item_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'product_id',
                ],
            ]);

            $this->forge->addForeignKey('flashsale_item_id', 'flashsale_items', 'id', 'SET NULL', 'CASCADE');
            $this->forge->processIndexes('orders');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('flashsale_item_id', 'orders')) {
            $this->forge->dropForeignKey('orders', 'orders_flashsale_item_id_foreign');
            $this->forge->dropColumn('orders', 'flashsale_item_id');
        }
    }
}