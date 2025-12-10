<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchiseApplicationsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if ($this->db->tableExists('franchise_applications')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'application_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'applicant_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'business_name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'proposed_location' => [
                'type' => 'TEXT',
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'investment_capital' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'business_experience' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'motivation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'under_review', 'approved', 'rejected', 'converted'],
                'default' => 'pending',
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'review_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('application_number');
        $this->forge->createTable('franchise_applications');
    }

    public function down()
    {
        $this->forge->dropTable('franchise_applications', true);
    }
}
