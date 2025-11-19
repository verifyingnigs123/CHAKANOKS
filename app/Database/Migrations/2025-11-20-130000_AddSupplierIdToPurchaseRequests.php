<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToPurchaseRequests extends Migration
{
    public function up()
    {
        // Only add column if it doesn't already exist
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('purchase_requests');
        if (! in_array('supplier_id', $fields)) {
            $this->forge->addColumn('purchase_requests', [
                'supplier_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                ]
            ]);

            // Add FK to suppliers
            $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'SET NULL', 'CASCADE');
        }
    }

    public function down()
    {
        if ($this->forge->hasColumn('purchase_requests', 'supplier_id')) {
            $this->forge->dropColumn('purchase_requests', 'supplier_id');
        }
    }
}
