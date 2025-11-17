<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockAlertsTable extends Migration
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
            'alert_type' => [
                'type'       => 'ENUM',
                'constraint' => ['low_stock', 'out_of_stock', 'expiring_soon', 'expired'],
            ],
            'current_quantity' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'threshold' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'acknowledged', 'resolved'],
                'default'    => 'active',
            ],
            'acknowledged_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'acknowledged_at' => [
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
        $this->forge->addForeignKey('acknowledged_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey('status');
        $this->forge->createTable('stock_alerts');
    }

    public function down()
    {
        $this->forge->dropTable('stock_alerts');
    }
}

