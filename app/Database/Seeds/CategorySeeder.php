<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => 'Beverages',
                'description' => 'Drinks and beverages',
                'status'      => 'active',
                'created_at'  => Time::now(),
                'updated_at'  => Time::now(),
            ],
            [
                'name'        => 'Snacks',
                'description' => 'Snack items and finger foods',
                'status'      => 'active',
                'created_at'  => Time::now(),
                'updated_at'  => Time::now(),
            ],
            [
                'name'        => 'Ingredients',
                'description' => 'Raw ingredients and supplies',
                'status'      => 'active',
                'created_at'  => Time::now(),
                'updated_at'  => Time::now(),
            ],
            [
                'name'        => 'Packaging',
                'description' => 'Packaging materials',
                'status'      => 'active',
                'created_at'  => Time::now(),
                'updated_at'  => Time::now(),
            ],
        ];

        $table = $this->db->table('categories');
        
        foreach ($data as $category) {
            $existing = $table->where('name', $category['name'])->get()->getRowArray();
            if ($existing) {
                $table->where('name', $category['name'])->update($category);
            } else {
                $table->insert($category);
            }
        }
    }
}
