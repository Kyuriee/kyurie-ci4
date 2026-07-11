<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCouponToOrders extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('coupon_id', 'orders')) {
            $this->forge->addColumn('orders', [
                'coupon_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'flashsale_item_id',
                ],
                'coupon_discount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'default'    => 0,
                    'after'      => 'coupon_id',
                ],
            ]);

            $this->forge->addForeignKey('coupon_id', 'coupons', 'id', 'SET NULL', 'CASCADE');
            $this->forge->processIndexes('orders');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('coupon_id', 'orders')) {
            $this->forge->dropForeignKey('orders', 'orders_coupon_id_foreign');
            $this->forge->dropColumn('orders', ['coupon_id', 'coupon_discount']);
        }
    }
}
