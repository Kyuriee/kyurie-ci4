<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTargetColumnInGames extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('games', [
            'target' => [
                'name'       => 'target',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => 'default',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('games', [
            'target' => [
                'name'       => 'target',
                'type'       => 'ENUM',
                'constraint' => ['default', 'zone', 'server'],
                'null'       => false,
                'default'    => 'default',
            ],
        ]);
    }
}