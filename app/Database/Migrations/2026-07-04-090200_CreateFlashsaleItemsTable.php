<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFlashsaleItemsTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('flashsale_items')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'flashsale_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'product_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'stock' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'sold' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'discount_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['fixed', 'percent'],
                    'default'    => 'fixed',
                ],
                'discount_value' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'default'    => 0,
                ],
                'sort_order' => [
                    'type'       => 'SMALLINT',
                    'constraint' => 5,
                    'unsigned'   => true,
                    'default'    => 0,
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
            $this->forge->addKey('flashsale_id');
            $this->forge->addKey('product_id');
            $this->forge->addKey('status');
            $this->forge->addForeignKey('flashsale_id', 'flashsale', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('product_id', 'product', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('flashsale_items', true, [
                'ENGINE' => 'InnoDB',
            ]);
        }

        // Jaga-jaga: kalau tabel udah ada duluan (kayak di DB dev lo) tapi belum ada kolom status
        if (! $this->db->fieldExists('status', 'flashsale_items')) {
            $this->forge->addColumn('flashsale_items', [
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['On', 'Off'],
                    'default'    => 'On',
                    'after'      => 'sort_order',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('flashsale_items', true);
    }
}