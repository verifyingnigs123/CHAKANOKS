<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use App\Models\BranchModel;
use App\Models\SupplierModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $branchModel = new BranchModel();
        $supplierModel = new SupplierModel();

        // Resolve branch ids (use main branch as default for branch users)
        $mainBranch = $branchModel->where('code', 'main_branch')->first();
        $northBranch = $branchModel->where('code', 'north_branch')->first();
        $southBranch = $branchModel->where('code', 'south_branch')->first();

        // Resolve a supplier to attach to the seeded supplier user (use first supplier)
        $firstSupplier = $supplierModel->orderBy('id', 'ASC')->first();

        $data = [
            [
                'username'      => 'centraladmin',
                'password'      => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'     => 'Central Administrator',
                'email'         => 'centraladmin@scms.com',
                'phone'         => '09999999999',
                'role'          => 'central_admin',
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
                'branch_id'     => $mainBranch ? $mainBranch['id'] : null,
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
                'branch_id'     => $mainBranch ? $mainBranch['id'] : null,
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
                'supplier_id'   => $firstSupplier ? $firstSupplier['id'] : null,
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
                'branch_id'     => $northBranch ? $northBranch['id'] : null,
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
                'branch_id'     => $southBranch ? $southBranch['id'] : null,
                'status'        => 'active',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        // Insert or update users (handles duplicates gracefully)
        $userTable = $this->db->table('users');
        
        foreach ($data as $user) {
            // Check by username first
            $existing = $userTable->where('username', $user['username'])->get()->getRowArray();
            
            // Also check for old sysadmin user to migrate it
            if ($user['username'] === 'centraladmin') {
                $oldSysadmin = $userTable->where('username', 'sysadmin')->get()->getRowArray();
                if ($oldSysadmin && !$existing) {
                    // Migrate old sysadmin to centraladmin
                    $userTable->where('username', 'sysadmin')->update([
                        'username' => 'centraladmin',
                        'email' => 'centraladmin@scms.com',
                        'role' => 'central_admin',
                        'full_name' => 'Central Administrator',
                        'password' => $user['password'],
                        'updated_at' => Time::now()
                    ]);
                    continue; // Skip to next user
                }
            }
            
            if ($existing) {
                // Update existing user
                $updateData = $user;
                // Uncomment the line below if you want to preserve existing passwords
                // unset($updateData['password']);
                $userTable->where('username', $user['username'])->update($updateData);
            } else {
                // Insert new user
                $userTable->insert($user);
            }
        }
    }
}
