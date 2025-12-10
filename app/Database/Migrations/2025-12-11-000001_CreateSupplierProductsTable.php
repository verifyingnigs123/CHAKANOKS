<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierProductsTable extends Migration
{
    public function up()
    {
        // Create supplier_products pivot table for many-to-many relationship
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'supplier_sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'supplier_price' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'min_order_qty' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'lead_time_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'is_preferred' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
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
        $this->forge->addKey(['supplier_id', 'product_id'], false, true); // Unique composite key
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('supplier_products', true);
    }

    public function down()
    {
        $this->forge->dropTable('supplier_products', true);
    }
}
