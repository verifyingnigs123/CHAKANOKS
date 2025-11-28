<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUnusedFieldsFromFranchiseApplicationsTable extends Migration
{
    public function up()
    {
        // Remove unused fields that are no longer in the form
        $this->forge->dropColumn('franchise_applications', [
            'budget_range',
            'business_experience',
            'reason',
            'desired_start_date'
        ]);
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

        $this->forge->addColumn('franchise_applications', $fields);
    }
}
