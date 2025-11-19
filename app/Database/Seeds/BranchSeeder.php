<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use App\Models\BranchModel;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $model = new BranchModel();
        $now = Time::now();

        $branches = [
            ['name' => 'Main Branch', 'code' => 'main_branch', 'address' => 'Main Street', 'status' => 'active'],
            ['name' => 'North Branch', 'code' => 'north_branch', 'address' => 'North Ave', 'status' => 'active'],
            ['name' => 'South Branch', 'code' => 'south_branch', 'address' => 'South Ave', 'status' => 'active'],
        ];

        foreach ($branches as $b) {
            $existing = $model->where('code', $b['code'])->orWhere('name', $b['name'])->first();
            $data = array_merge($b, ['created_at' => $now, 'updated_at' => $now]);
            if ($existing) {
                $model->update($existing['id'], $data);
            } else {
                $model->insert($data);
            }
        }
    }
}
