<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowNullProductIdInPurchaseRequestItems extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraint on product_id
        try {
            $this->forge->dropForeignKey('purchase_request_items', 'purchase_request_items_product_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name, try alternative names
            try {
                $this->db->query('ALTER TABLE purchase_request_items DROP FOREIGN KEY purchase_request_items_ibfk_2');
            } catch (\Exception $e2) {
                // Ignore if already dropped
            }
        }
        
        // Modify product_id to allow NULL
        $this->forge->modifyColumn('purchase_request_items', [
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Revert product_id to NOT NULL
        $this->forge->modifyColumn('purchase_request_items', [
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
        ]);
        
        // Re-add foreign key
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
    }
}
