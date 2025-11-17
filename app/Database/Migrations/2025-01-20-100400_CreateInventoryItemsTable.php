<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'inventory_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'batch_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'cost_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'received_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'reserved', 'expired', 'damaged', 'sold'],
                'default'    => 'available',
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
        $this->forge->addForeignKey('inventory_id', 'inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('expiry_date');
        $this->forge->createTable('inventory_items');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_items');
    }
}

