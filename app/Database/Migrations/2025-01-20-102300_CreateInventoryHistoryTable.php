<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryHistoryTable extends Migration
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
            'purchase_order_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'delivery_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'po_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'delivery_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'quantity_added' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'previous_quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'new_quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'transaction_type' => [
                'type'       => 'ENUM',
                'constraint' => ['delivery_received', 'transfer_in', 'adjustment', 'manual_update'],
                'default'    => 'delivery_received',
            ],
            'received_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('delivery_id', 'deliveries', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey(['branch_id', 'product_id']);
        $this->forge->addKey('created_at');
        $this->forge->createTable('inventory_history');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_history');
    }
}

