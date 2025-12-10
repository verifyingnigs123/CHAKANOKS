<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePurchaseRequestItemsForSupplierProducts extends Migration
{
    public function up()
    {
        // Add supplier_product_id and product details columns to purchase_request_items
        $this->forge->addColumn('purchase_request_items', [
            'supplier_product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'product_id',
            ],
            'product_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'supplier_product_id',
            ],
            'product_sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'product_name',
            ],
            'product_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'pcs',
                'after' => 'product_sku',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('purchase_request_items', ['supplier_product_id', 'product_name', 'product_sku', 'product_unit']);
    }
}
