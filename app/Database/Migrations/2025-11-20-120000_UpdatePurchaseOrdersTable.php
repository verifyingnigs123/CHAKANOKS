<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePurchaseOrdersTable extends Migration
{
    public function up()
    {
        // Add new datetime/user fields and extend status to include 'prepared'
        // Modify enum via raw query (safer across DBs used in project)
        $this->db->query("ALTER TABLE `purchase_orders` MODIFY COLUMN `status` ENUM('draft','sent','confirmed','partial','completed','cancelled','prepared') NOT NULL DEFAULT 'draft'");

        $fields = [
            'prepared_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'prepared_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'confirmed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('purchase_orders', $fields);
    }

    public function down()
    {
        // Drop added columns and revert status enum
        $this->forge->dropColumn('purchase_orders', ['prepared_at', 'prepared_by', 'sent_at', 'confirmed_at']);
        $this->db->query("ALTER TABLE `purchase_orders` MODIFY COLUMN `status` ENUM('draft','sent','confirmed','partial','completed','cancelled') NOT NULL DEFAULT 'draft'");
    }
}
