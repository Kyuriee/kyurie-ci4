<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublisherToGames extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('publisher', 'games')) {
            $this->forge->addColumn('games', [
                'publisher' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'games',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('publisher', 'games')) {
            $this->forge->dropColumn('games', 'publisher');
        }
    }
}