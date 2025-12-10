<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run seeders in correct order (dependencies first)
        
        // 1. Categories (products need category_id)
        $this->call('CategorySeeder');
        
        // 2. Suppliers (users need supplier_id for supplier role)
        $this->call('SupplierSeeder');
        
        // 3. Users last (depends on suppliers)
        $this->call('UserSeeder');
    }
}
