<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToBranches extends Migration
{
    public function up()
    {
        // Check if column already exists
        if ($this->db->tableExists('branches') && !$this->db->fieldExists('type', 'branches')) {
            $this->forge->addColumn('branches', [
                'type' => [
                    'type' => 'ENUM',
                    'constraint' => ['main', 'branch', 'franchise'],
                    'default' => 'branch',
                    'after' => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('type', 'branches')) {
            $this->forge->dropColumn('branches', 'type');
        }
    }
}
