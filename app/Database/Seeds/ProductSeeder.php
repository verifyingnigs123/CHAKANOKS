<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductModel;
use App\Models\SupplierModel;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $productModel = new ProductModel();
        $supplierModel = new SupplierModel();

        $mapping = [
            'Chicken Supplier' => [
                ['name' => 'Chicken Wings', 'sku' => 'CH-WING', 'unit' => 'kg', 'cost_price' => 120.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Chicken Leg', 'sku' => 'CH-LEG', 'unit' => 'kg', 'cost_price' => 150.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Whole Chicken', 'sku' => 'CH-WHOLE', 'unit' => 'pcs', 'cost_price' => 300.00, 'selling_price' => 0.00, 'status' => 'active'],
            ],
            'Utensils Supplier' => [
                ['name' => 'Serving Spoons', 'sku' => 'UT-SPOON', 'unit' => 'pcs', 'cost_price' => 50.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Tongs', 'sku' => 'UT-TONG', 'unit' => 'pcs', 'cost_price' => 80.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Chef Knife', 'sku' => 'UT-KNIFE', 'unit' => 'pcs', 'cost_price' => 300.00, 'selling_price' => 0.00, 'status' => 'active'],
            ],
            'Stove Supplier' => [
                ['name' => 'Gas Stove', 'sku' => 'ST-GAS', 'unit' => 'pcs', 'cost_price' => 2500.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Burner', 'sku' => 'ST-BURN', 'unit' => 'pcs', 'cost_price' => 800.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Induction Cooker', 'sku' => 'ST-IND', 'unit' => 'pcs', 'cost_price' => 4500.00, 'selling_price' => 0.00, 'status' => 'active'],
            ],
            'Accessories Supplier' => [
                ['name' => 'Gloves', 'sku' => 'AC-GLOV', 'unit' => 'box', 'cost_price' => 200.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Aprons', 'sku' => 'AC-APR', 'unit' => 'pcs', 'cost_price' => 120.00, 'selling_price' => 0.00, 'status' => 'active'],
                ['name' => 'Storage Container', 'sku' => 'AC-STORE', 'unit' => 'pcs', 'cost_price' => 180.00, 'selling_price' => 0.00, 'status' => 'active'],
            ],
        ];

        foreach ($mapping as $supplierName => $products) {
            $supplier = $supplierModel->where('name', $supplierName)->first();
            if (!$supplier) continue; // supplier missing (shouldn't happen if SupplierSeeder ran)

            foreach ($products as $p) {
                $existing = $productModel->where('name', $p['name'])->where('supplier_id', $supplier['id'])->first();
                if (!$existing) {
                    $productModel->insert(array_merge($p, ['supplier_id' => $supplier['id']]));
                }
            }
        }
    }
}
