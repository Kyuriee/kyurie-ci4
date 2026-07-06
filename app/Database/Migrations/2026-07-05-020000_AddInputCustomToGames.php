<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInputCustomToGames extends Migration
{
    public function up()
    {
        $this->forge->addColumn('games', [
            'input_custom' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'target',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('games', 'input_custom');
    }
}
