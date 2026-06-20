<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up()
    {
        /*
         * Banner
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'subtitle' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
            ],
            'date_start' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'date_end' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('status');
        $this->forge->createTable('banner', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Flashsale
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'date_start' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'date_end' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
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
        $this->forge->addKey('status');
        $this->forge->createTable('flashsale', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Game Categories
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
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
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('status');
        $this->forge->createTable('game_categories', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Games
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'game_category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'games' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'banner' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'target' => [
                'type'       => 'ENUM',
                'constraint' => ['default', 'zone', 'server'],
                'default'    => 'default',
            ],
            'is_popular' => [
                'type'       => 'ENUM',
                'constraint' => ['Y', 'N'],
                'default'    => 'N',
            ],
            'sort' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
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
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('game_category_id');
        $this->forge->addKey('status');
        $this->forge->addKey('is_popular');
        $this->forge->addForeignKey('game_category_id', 'game_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('games', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Product
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'games_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'flashsale_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'product' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'raw_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'discount_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'flashsale_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'sold' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'sort' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
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
        $this->forge->addKey('games_id');
        $this->forge->addKey('flashsale_id');
        $this->forge->addKey('sku');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('games_id', 'games', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('flashsale_id', 'flashsale', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('product', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Users
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'level' => [
                'type'       => 'ENUM',
                'constraint' => ['Member', 'Silver', 'Gold'],
                'default'    => 'Member',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
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
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('status');
        $this->forge->createTable('users', true, [
            'ENGINE' => 'InnoDB',
        ]);

        /*
         * Admin
         */
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'level' => [
                'type'       => 'ENUM',
                'constraint' => ['Superadmin', 'Admin'],
                'default'    => 'Admin',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['On', 'Off'],
                'default'    => 'On',
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('status');
        $this->forge->createTable('admin', true, [
            'ENGINE' => 'InnoDB',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('product', true);
        $this->forge->dropTable('games', true);
        $this->forge->dropTable('game_categories', true);
        $this->forge->dropTable('flashsale', true);
        $this->forge->dropTable('banner', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('admin', true);
    }
}