<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFranchiseApplicationsTableMakeFieldsNullable extends Migration
{
    public function up()
    {
        // Make unused fields nullable since they're no longer in the form
        $fields = [
            'budget_range' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'business_experience' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'desired_start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('franchise_applications', $fields);
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
