<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFranchiseApplicationsTableMakeFieldsNullable extends Migration
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
        
        // Make unused fields nullable since they're no longer in the form
        // Only modify columns that exist
        $fieldsToModify = [];
        
        if (in_array('budget_range', $existingColumns)) {
            $fieldsToModify['budget_range'] = [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ];
        }
        
        if (in_array('business_experience', $existingColumns)) {
            $fieldsToModify['business_experience'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }
        
        if (in_array('reason', $existingColumns)) {
            $fieldsToModify['reason'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }
        
        if (in_array('desired_start_date', $existingColumns)) {
            $fieldsToModify['desired_start_date'] = [
                'type' => 'DATE',
                'null' => true,
            ];
        }

        // Only modify if there are fields to modify
        if (!empty($fieldsToModify)) {
            $this->forge->modifyColumn('franchise_applications', $fieldsToModify);
        }
    }

    public function down()
    {
        // Revert fields to NOT NULL (original state)
        $fields = [
            'budget_range' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'business_experience' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'desired_start_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('franchise_applications', $fields);
    }
}
