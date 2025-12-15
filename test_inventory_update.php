<?php
/**
 * Test script to diagnose inventory update issues
 * Run this from command line: php test_inventory_update.php
 */

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = require_once FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require_once SYSTEMPATH . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

use App\Models\InventoryModel;
use App\Models\ProductModel;
use App\Models\BranchModel;

echo "=== Inventory Update Test ===\n\n";

// Get models
$inventoryModel = new InventoryModel();
$productModel = new ProductModel();
$branchModel = new BranchModel();

// Get first branch
$branch = $branchModel->first();
if (!$branch) {
    echo "ERROR: No branches found in database\n";
    exit(1);
}
echo "Branch: {$branch['name']} (ID: {$branch['id']})\n";

// Get first product
$product = $productModel->first();
if (!$product) {
    echo "ERROR: No products found in database\n";
    exit(1);
}
echo "Product: {$product['name']} (ID: {$product['id']})\n\n";

// Check current inventory
$currentInventory = $inventoryModel->where('branch_id', $branch['id'])
    ->where('product_id', $product['id'])
    ->first();

if ($currentInventory) {
    echo "Current Inventory: {$currentInventory['quantity']} units\n";
} else {
    echo "No inventory record exists yet\n";
}

// Test update
echo "\nTesting inventory update...\n";
$testQuantity = ($currentInventory['quantity'] ?? 0) + 10;
echo "Attempting to set quantity to: {$testQuantity}\n";

try {
    $result = $inventoryModel->updateQuantity($branch['id'], $product['id'], $testQuantity, 1);
    
    if ($result) {
        echo "✓ Update successful!\n";
        
        // Verify
        $updatedInventory = $inventoryModel->where('branch_id', $branch['id'])
            ->where('product_id', $product['id'])
            ->first();
        
        if ($updatedInventory) {
            echo "✓ Verified: Quantity is now {$updatedInventory['quantity']}\n";
        } else {
            echo "✗ ERROR: Could not find inventory record after update\n";
        }
    } else {
        echo "✗ Update failed\n";
        echo "Errors: " . json_encode($inventoryModel->errors()) . "\n";
    }
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
