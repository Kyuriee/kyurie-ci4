<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentTokenToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'payment_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'invoice',
            ],
        ]);

        $this->forge->addKey('payment_token');
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'payment_token');
    }
}
