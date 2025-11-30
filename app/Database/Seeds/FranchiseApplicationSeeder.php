<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FranchiseApplicationSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('franchise_applications')) {
            echo "Table 'franchise_applications' does not exist. Please run migrations first.\n";
            return;
        }
        
        // Sample franchise application data
        $data = [
            [
                'full_name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@example.com',
                'phone_number' => '+63 912 345 6789',
                'address' => '123 Main Street, Barangay Poblacion, Davao City, Davao del Sur',
                'status' => 'pending',
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => null,
            ],
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'phone_number' => '+63 923 456 7890',
                'address' => '456 Rizal Avenue, Cebu City, Cebu',
                'status' => 'reviewing',
                'notes' => 'Application under review. Waiting for additional documents.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'full_name' => 'Carlos Rodriguez',
                'email' => 'carlos.rodriguez@example.com',
                'phone_number' => '+63 934 567 8901',
                'address' => '789 EDSA, Quezon City, Metro Manila',
                'status' => 'approved',
                'notes' => 'Application approved. Franchise agreement sent.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'full_name' => 'Ana Garcia',
                'email' => 'ana.garcia@example.com',
                'phone_number' => '+63 945 678 9012',
                'address' => '321 Bonifacio Street, Makati City, Metro Manila',
                'status' => 'rejected',
                'notes' => 'Application rejected due to incomplete requirements.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'full_name' => 'Roberto Tan',
                'email' => 'roberto.tan@example.com',
                'phone_number' => '+63 956 789 0123',
                'address' => '654 Magsaysay Avenue, Iloilo City, Iloilo',
                'status' => 'pending',
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => null,
            ],
        ];
        
        // Insert data only if table is empty (to avoid duplicates)
        $existingCount = $db->table('franchise_applications')->countAllResults();
        
        if ($existingCount == 0) {
            $db->table('franchise_applications')->insertBatch($data);
            echo "Seeded {$db->affectedRows()} franchise applications.\n";
        } else {
            echo "Franchise applications table already contains data. Skipping seeder.\n";
        }
    }
}

