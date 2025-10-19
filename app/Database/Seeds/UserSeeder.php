<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'      => 'sysadmin',
                'password'      => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'     => 'System Administrator',
                'email'         => 'sysadmin@scms.com',
                'phone'         => '09999999999',
                'role'          => 'system_admin',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'username'      => 'branchmanager',
                'password'      => password_hash('branch123', PASSWORD_DEFAULT),
                'full_name'     => 'Branch Manager',
                'email'         => 'branchmanager@scms.com',
                'phone'         => '09951112222',
                'role'          => 'branch_manager',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'username'      => 'inventory',
                'password'      => password_hash('inventory123', PASSWORD_DEFAULT),
                'full_name'     => 'Inventory Staff',
                'email'         => 'inventory@scms.com',
                'phone'         => '09953334444',
                'role'          => 'inventory_staff',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'username'      => 'supplier',
                'password'      => password_hash('supplier123', PASSWORD_DEFAULT),
                'full_name'     => 'Supplier Account',
                'email'         => 'supplier@scms.com',
                'phone'         => '09954445555',
                'role'          => 'supplier',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'username'      => 'logistics',
                'password'      => password_hash('logistics123', PASSWORD_DEFAULT),
                'full_name'     => 'Logistics Coordinator',
                'email'         => 'logistics@scms.com',
                'phone'         => '09955556666',
                'role'          => 'logistics_coordinator',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'username'      => 'franchise',
                'password'      => password_hash('franchise123', PASSWORD_DEFAULT),
                'full_name'     => 'Franchise Manager',
                'email'         => 'franchise@scms.com',
                'phone'         => '09957778888',
                'role'          => 'franchise_manager',
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        // Insert multiple users at once
        $this->db->table('users')->insertBatch($data);
    }
}
