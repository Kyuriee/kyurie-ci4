<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropProviderColumnsFromOrders extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('orders', [
            'game_provider',
            'payment_provider',
        ]);
    }

    public function down()
    {
        $this->forge->addColumn('orders', [
            'game_provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'zone_id',
            ],
            'payment_provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'game_provider',
            ],
        ]);
    }
}
