<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCreatedByToSupplierProducts extends Migration
{
    public function up()
    {
        // Add created_by column to track which user created the product
        $this->forge->addColumn('supplier_products', [
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'supplier_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('supplier_products', 'created_by');
    }
}
