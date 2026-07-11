<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGuestIpToOrders extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('guest_ip', 'orders')) {
            $this->forge->addColumn('orders', [
                'guest_ip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 45,
                    'null'       => true,
                    'after'      => 'user_id',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('guest_ip', 'orders')) {
            $this->forge->dropColumn('orders', 'guest_ip');
        }
    }
}
