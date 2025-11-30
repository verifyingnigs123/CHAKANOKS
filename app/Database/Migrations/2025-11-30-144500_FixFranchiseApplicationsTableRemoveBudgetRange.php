<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixFranchiseApplicationsTableRemoveBudgetRange extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('franchise_applications')) {
            return;
        }
        
        // Get column information
        $fields = $db->getFieldData('franchise_applications');
        $existingColumns = array_column($fields, 'name');
        
        // Remove budget_range column if it exists
        if (in_array('budget_range', $existingColumns)) {
            $this->forge->dropColumn('franchise_applications', 'budget_range');
        }
        
        // Remove other unused columns if they exist
        $columnsToRemove = ['business_experience', 'reason', 'desired_start_date'];
        foreach ($columnsToRemove as $column) {
            if (in_array($column, $existingColumns)) {
                $this->forge->dropColumn('franchise_applications', $column);
            }
        }
    }

    public function down()
    {
        // Re-add the columns if we need to rollback
        $fields = [
            'budget_range' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'address',
            ],
            'business_experience' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'budget_range',
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'business_experience',
            ],
            'desired_start_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'reason',
            ],
        ];

        $db = \Config\Database::connect();
        $existingFields = $db->getFieldData('franchise_applications');
        $existingColumns = array_column($existingFields, 'name');
        
        foreach ($fields as $column => $definition) {
            if (!in_array($column, $existingColumns)) {
                $this->forge->addColumn('franchise_applications', [$column => $definition]);
            }
        }
    }
}

