<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingTables extends Migration
{
    public function up()
    {
        /*
         * Utilities
         * Untuk setting global website:
         * web_name, web_logo, web_description, maintenance, etc.
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'u_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'u_value' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'text',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_public' => [
                'type'       => 'ENUM',
                'constraint' => ['Y', 'N'],
                'default'    => 'Y',
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
        $this->forge->addUniqueKey('u_key');
        $this->forge->addKey('is_public');
        $this->forge->createTable('utilities', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Credentials
         * Untuk API key, secret, merchant id, endpoint, token provider.
         * Jangan tampilkan ke view public.
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'c_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'c_value' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'text',
            ],
            'mode' => [
                'type'       => 'ENUM',
                'constraint' => ['sandbox', 'production'],
                'default'    => 'production',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('provider');
        $this->forge->addKey('mode');
        $this->forge->addKey('status');
        $this->forge->addUniqueKey(['provider', 'c_key', 'mode']);
        $this->forge->createTable('credentials', true, [
            'ENGINE' => 'InnoDB',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('credentials', true);
        $this->forge->dropTable('utilities', true);
    }
}