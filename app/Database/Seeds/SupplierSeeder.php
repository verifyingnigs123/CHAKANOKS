<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'           => 'ABC Food Supplies',
                'code'           => 'SUP001',
                'contact_person' => 'Juan Dela Cruz',
                'email'          => 'abc@supplier.com',
                'phone'          => '09171234567',
                'address'        => '100 Supplier Street, Manila',
                'payment_terms'  => 'Net 30',
                'delivery_terms' => '3-5 business days',
                'status'         => 'active',
                'created_at'     => Time::now(),
                'updated_at'     => Time::now(),
            ],
            [
                'name'           => 'XYZ Trading Corp',
                'code'           => 'SUP002',
                'contact_person' => 'Maria Santos',
                'email'          => 'xyz@supplier.com',
                'phone'          => '09189876543',
                'address'        => '200 Trade Avenue, Quezon City',
                'payment_terms'  => 'Net 15',
                'delivery_terms' => '1-2 business days',
                'status'         => 'active',
                'created_at'     => Time::now(),
                'updated_at'     => Time::now(),
            ],
        ];

        $table = $this->db->table('suppliers');
        
        foreach ($data as $supplier) {
            $existing = $table->where('code', $supplier['code'])->get()->getRowArray();
            if ($existing) {
                $table->where('code', $supplier['code'])->update($supplier);
            } else {
                $table->insert($supplier);
            }
        }
    }
}
