<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('coupons')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'discount_percent' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'null'       => true,
                ],
                'discount_nominal' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'null'       => true,
                ],
                'max_discount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'null'       => true,
                ],
                'min_transaction' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'default'    => 0,
                ],
                'type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['general', 'custom'],
                    'default'    => 'general',
                ],
                // CSV of user.level values this coupon applies to when type=custom.
                // e.g. "guest,silver,gold". NULL/empty = not restricted by level.
                'level_csv' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                // CSV of games.id values this coupon applies to when type=custom.
                'game_csv' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                // CSV of product.id values this coupon applies to when type=custom.
                'product_csv' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'max_per_guest' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'max_per_user' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'max_global' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'max_per_daily' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                // Atomic global counter, guarded against max_global at consume time.
                // Mirrors flashsale_items.sold. Per-user/guest/daily limits are
                // enforced by counting coupon_usages instead (can't be a single
                // counter since they're scoped, not global).
                'usage_count' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'valid_from' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'valid_until' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['On', 'Off'],
                    'default'    => 'On',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('code');
            $this->forge->addKey('status');
            $this->forge->createTable('coupons', true, [
                'ENGINE' => 'InnoDB',
            ]);
        }

        if (! $this->db->tableExists('coupon_usages')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'coupon_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'order_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'guest_ip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 45,
                    'null'       => true,
                ],
                'discount_amount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('coupon_id');
            $this->forge->addKey('order_id');
            $this->forge->addKey('user_id');
            $this->forge->addKey('guest_ip');
            $this->forge->addKey(['coupon_id', 'created_at']);
            $this->forge->addForeignKey('coupon_id', 'coupons', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('coupon_usages', true, [
                'ENGINE' => 'InnoDB',
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('coupon_usages', true);
        $this->forge->dropTable('coupons', true);
    }
}
