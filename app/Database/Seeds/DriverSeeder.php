<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run()
    {
        $drivers = [
            [
                'name' => 'Juan Dela Cruz',
                'vehicle_number' => 'ABC-1234',
                'phone' => '09123456789',
                'license_number' => 'DL-001234',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pedro Santos',
                'vehicle_number' => 'XYZ-5678',
                'phone' => '09187654321',
                'license_number' => 'DL-002345',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Maria Garcia',
                'vehicle_number' => 'DEF-9012',
                'phone' => '09234567890',
                'license_number' => 'DL-003456',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Carlos Rodriguez',
                'vehicle_number' => 'GHI-3456',
                'phone' => '09345678901',
                'license_number' => 'DL-004567',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ana Martinez',
                'vehicle_number' => 'JKL-7890',
                'phone' => '09456789012',
                'license_number' => 'DL-005678',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder to insert
        $this->db->table('drivers')->insertBatch($drivers);
    }
}

