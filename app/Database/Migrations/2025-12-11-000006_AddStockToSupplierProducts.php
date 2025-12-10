<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockToSupplierProducts extends Migration
{
    public function up()
    {
        // Add stock_quantity field for suppliers to track their own inventory
        $fields = [
            'stock_quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'supplier_price',
            ],
        ];

        $this->forge->addColumn('supplier_products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('supplier_products', ['stock_quantity']);
    }
}
