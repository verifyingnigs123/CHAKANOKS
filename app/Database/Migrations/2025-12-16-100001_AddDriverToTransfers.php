<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDriverToTransfers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transfers', [
            'driver_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'dispatched_at'
            ],
            'driver_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'driver_id'
            ],
            'driver_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'driver_name'
            ],
            'vehicle_info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'driver_phone'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transfers', ['driver_id', 'driver_name', 'driver_phone', 'vehicle_info']);
    }
}
