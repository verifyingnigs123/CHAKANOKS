<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'delivery_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'purchase_order_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'in_transit', 'delivered', 'partial', 'cancelled'],
                'default'    => 'scheduled',
            ],
            'scheduled_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'received_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'received_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'driver_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'vehicle_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'notes' => [
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
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('deliveries');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}

