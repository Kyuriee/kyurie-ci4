<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NullableGuestOrderColumns extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('user_id', 'orders')) {
            $this->dropForeignKeyIfExists('orders', 'orders_user_id_foreign');

            $this->forge->modifyColumn('orders', [
                'user_id' => [
                    'name'       => 'user_id',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
            ]);

            $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
            $this->forge->processIndexes('orders');
        }

        if ($this->db->fieldExists('flashsale_item_id', 'orders')) {
            $this->forge->modifyColumn('orders', [
                'flashsale_item_id' => [
                    'name'       => 'flashsale_item_id',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('user_id', 'orders')) {
            $this->dropForeignKeyIfExists('orders', 'orders_user_id_foreign');

            $this->forge->modifyColumn('orders', [
                'user_id' => [
                    'name'       => 'user_id',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                ],
            ]);

            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->processIndexes('orders');
        }
    }

    protected function dropForeignKeyIfExists(string $table, string $foreignKey): void
    {
        try {
            $this->forge->dropForeignKey($table, $foreignKey);
        } catch (\Throwable $e) {
            // Some databases/environments may already have the desired FK removed.
        }
    }
}
