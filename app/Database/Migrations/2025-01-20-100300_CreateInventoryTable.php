<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'reserved_quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'available_quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'last_updated_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'last_updated_at' => [
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
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('last_updated_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addUniqueKey(['branch_id', 'product_id']);
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}

