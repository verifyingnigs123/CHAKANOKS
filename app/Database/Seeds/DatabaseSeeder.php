<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run core seeders in order (avoid duplicates inside the seeders themselves)
        $this->call('SupplierSeeder');
        $this->call('ProductSeeder');

        // Branches must exist before users so we can assign branch_id
        if (class_exists('\App\Database\Seeds\BranchSeeder')) {
            $this->call('BranchSeeder');
        }

        // Existing seeders
        if (class_exists('\App\Database\Seeds\DriverSeeder')) {
            $this->call('DriverSeeder');
        }

        if (class_exists('\App\Database\Seeds\UserSeeder')) {
            $this->call('UserSeeder');
        }
    }
}
