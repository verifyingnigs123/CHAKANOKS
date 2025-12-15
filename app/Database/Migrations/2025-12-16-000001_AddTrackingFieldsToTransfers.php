<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrackingFieldsToTransfers extends Migration
{
    public function up()
    {
        // Add new fields for tracking
        $fields = [
            'scheduled_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'approved_at'
            ],
            'scheduled_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'scheduled_date'
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'scheduled_by'
            ],
            'dispatched_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'scheduled_at'
            ],
            'dispatched_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'dispatched_by'
            ],
            'received_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'dispatched_at'
            ],
            'received_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'received_by'
            ],
        ];

        $this->forge->addColumn('transfers', $fields);

        // Update status enum to include 'scheduled'
        $this->db->query("ALTER TABLE transfers MODIFY COLUMN status ENUM('pending', 'approved', 'scheduled', 'in_transit', 'completed', 'rejected', 'cancelled') DEFAULT 'pending'");

        // Add foreign keys with unique names
        $this->db->query("ALTER TABLE transfers ADD CONSTRAINT fk_transfers_scheduled_by FOREIGN KEY (scheduled_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE transfers ADD CONSTRAINT fk_transfers_dispatched_by FOREIGN KEY (dispatched_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE transfers ADD CONSTRAINT fk_transfers_received_by FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        // Drop foreign keys first
        $this->db->query("ALTER TABLE transfers DROP FOREIGN KEY fk_transfers_scheduled_by");
        $this->db->query("ALTER TABLE transfers DROP FOREIGN KEY fk_transfers_dispatched_by");
        $this->db->query("ALTER TABLE transfers DROP FOREIGN KEY fk_transfers_received_by");

        // Drop columns
        $this->forge->dropColumn('transfers', [
            'scheduled_date',
            'scheduled_by',
            'scheduled_at',
            'dispatched_by',
            'dispatched_at',
            'received_by',
            'received_at'
        ]);

        // Revert status enum
        $this->db->query("ALTER TABLE transfers MODIFY COLUMN status ENUM('pending', 'approved', 'in_transit', 'completed', 'rejected', 'cancelled') DEFAULT 'pending'");
    }
}
