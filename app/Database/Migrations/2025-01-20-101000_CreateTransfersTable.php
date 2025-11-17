<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransfersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transfer_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'from_branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'to_branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'requested_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'approved_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'in_transit', 'completed', 'rejected', 'cancelled'],
                'default'    => 'pending',
            ],
            'request_date' => [
                'type' => 'DATE',
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
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
        $this->forge->addForeignKey('from_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('to_branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('requested_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('transfers');
    }
}

