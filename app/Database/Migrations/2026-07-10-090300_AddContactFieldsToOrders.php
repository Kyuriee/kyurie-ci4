<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContactFieldsToOrders extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('contact_email', 'orders')) {
            $this->forge->addColumn('orders', [
                'contact_email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                    'after'      => 'zone_id',
                ],
                'contact_phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => 'contact_email',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('contact_email', 'orders')) {
            $this->forge->dropColumn('orders', ['contact_email', 'contact_phone']);
        }
    }
}
