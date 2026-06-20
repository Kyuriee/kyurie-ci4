<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthTokensToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'remember_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reset_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('remember_token');
        $this->forge->addKey('reset_token');
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'remember_token');
        $this->forge->dropColumn('users', 'reset_token');
        $this->forge->dropColumn('users', 'reset_expires_at');
    }
}
