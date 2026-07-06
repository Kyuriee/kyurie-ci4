<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WidenOrderTargetColumns extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('orders', [
            'customer_id' => [
                'name'       => 'customer_id',
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'zone_id' => [
                'name'       => 'zone_id',
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('orders', [
            'customer_id' => [
                'name'       => 'customer_id',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'zone_id' => [
                'name'       => 'zone_id',
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);
    }
}
