<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchisesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'unique'     => true,
            ],
            'franchise_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'owner_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'contact_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'contact_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'agreement_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'royalty_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'active', 'suspended', 'terminated'],
                'default'    => 'pending',
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
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('franchises');
    }

    public function down()
    {
        $this->forge->dropTable('franchises');
    }
}

