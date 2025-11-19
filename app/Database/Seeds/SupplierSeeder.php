<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\SupplierModel;
use CodeIgniter\I18n\Time;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $model = new SupplierModel();

        $now = Time::now();

        $base = [
            ['name' => 'Chicken Supplier', 'contact_person' => 'Chicken Co'],
            ['name' => 'Utensils Supplier', 'contact_person' => 'Utensils Co'],
            ['name' => 'Stove Supplier', 'contact_person' => 'Stove Co'],
            ['name' => 'Accessories Supplier', 'contact_person' => 'Accessories Co'],
        ];

        foreach ($base as $sup) {
            $code = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $sup['name']));
            $data = [
                'name' => $sup['name'],
                'code' => $code,
                'contact_person' => $sup['contact_person'],
                'email' => null,
                'phone' => null,
                'address' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $existing = $model->where('name', $data['name'])->orWhere('code', $data['code'])->first();
            if ($existing) {
                // Update existing record to ensure required fields exist
                $model->update($existing['id'], $data);
            } else {
                $model->insert($data);
            }
        }
    }
}
