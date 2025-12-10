<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryIdToProducts extends Migration
{
    public function up()
    {
        // Check if table exists first
        if (!$this->db->tableExists('products')) {
            return;
        }

        // Check if column already exists
        $existingFields = $this->db->getFieldNames('products');
        
        if (!in_array('category_id', $existingFields)) {
            $this->forge->addColumn('products', [
                'category_id' => [
                    'type'       => 'INT',
                    'unsigned'   => true,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('products')) {
            return;
        }

        $existingFields = $this->db->getFieldNames('products');
        
        if (in_array('category_id', $existingFields)) {
            $this->forge->dropColumn('products', 'category_id');
        }
    }
}
