<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSupplierProductsForOwnProducts extends Migration
{
    public function up()
    {
        // Add fields for supplier's own products (not linked to system products)
        $fields = [
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'after' => 'product_id',
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'name',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'sku',
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pcs',
                'after' => 'description',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'unit',
            ],
        ];

        $this->forge->addColumn('supplier_products', $fields);
        
        // Modify product_id to allow null
        $this->forge->modifyColumn('supplier_products', [
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        
        // Drop the foreign key constraint on product_id to allow null
        try {
            $this->forge->dropForeignKey('supplier_products', 'supplier_products_product_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name
        }
    }

    public function down()
    {
        $this->forge->dropColumn('supplier_products', ['name', 'sku', 'description', 'unit', 'category']);
    }
}
