<?php
/**
 * Simple Fix Script for Transfer TRF20251215001
 * 
 * Run this from command line: php fix_transfer_simple.php
 */

// Load CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

$pathsConfig = FCPATH . 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get database
$db = \Config\Database::connect();

echo "===========================================\n";
echo "Fix Transfer TRF20251215001\n";
echo "===========================================\n\n";

// Get transfer
$transfer = $db->table('transfers')
    ->where('transfer_number', 'TRF20251215001')
    ->get()
    ->getRowArray();

if (!$transfer) {
    echo "❌ Transfer not found!\n";
    exit(1);
}

echo "Transfer: {$transfer['transfer_number']}\n";
echo "Status: {$transfer['status']}\n";
echo "From Branch: {$transfer['from_branch_id']}\n";
echo "To Branch: {$transfer['to_branch_id']}\n\n";

// Get items
$items = $db->table('transfer_items')
    ->select('transfer_items.*, products.name')
    ->join('products', 'products.id = transfer_items.product_id')
    ->where('transfer_id', $transfer['id'])
    ->get()
    ->getResultArray();

echo "Items:\n";
foreach ($items as $item) {
    echo "  - {$item['name']}: {$item['quantity']} units\n";
}
echo "\n";

// Start transaction
$db->transBegin();

try {
    // Deduct from source
    echo "Deducting from source branch...\n";
    foreach ($items as $item) {
        $inv = $db->table('inventory')
            ->where('branch_id', $transfer['from_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();
        
        if ($inv) {
            $oldQty = $inv['quantity'];
            $newQty = $oldQty - $item['quantity'];
            
            $db->table('inventory')
                ->where('id', $inv['id'])
                ->update([
                    'quantity' => $newQty,
                    'available_quantity' => $newQty,
                    'last_updated_at' => date('Y-m-d H:i:s')
                ]);
            
            echo "  ✓ {$item['name']}: {$oldQty} → {$newQty}\n";
        }
    }
    
    // Add to destination
    echo "\nAdding to destination branch...\n";
    foreach ($items as $item) {
        $inv = $db->table('inventory')
            ->where('branch_id', $transfer['to_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();
        
        if ($inv) {
            $oldQty = $inv['quantity'];
            $newQty = $oldQty + $item['quantity'];
            
            $db->table('inventory')
                ->where('id', $inv['id'])
                ->update([
                    'quantity' => $newQty,
                    'available_quantity' => $newQty,
                    'last_updated_at' => date('Y-m-d H:i:s')
                ]);
            
            echo "  ✓ {$item['name']}: {$oldQty} → {$newQty}\n";
        } else {
            $db->table('inventory')->insert([
                'branch_id' => $transfer['to_branch_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'available_quantity' => $item['quantity'],
                'last_updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            echo "  ✓ {$item['name']}: 0 → {$item['quantity']} (new)\n";
        }
        
        // Update received
        $db->table('transfer_items')
            ->where('id', $item['id'])
            ->update(['quantity_received' => $item['quantity']]);
    }
    
    // Update transfer
    echo "\nUpdating transfer status...\n";
    $db->table('transfers')
        ->where('id', $transfer['id'])
        ->update([
            'status' => 'completed',
            'dispatched_by' => $transfer['approved_by'],
            'dispatched_at' => date('Y-m-d H:i:s'),
            'received_by' => $transfer['approved_by'],
            'received_at' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    
    echo "  ✓ Status updated to completed\n";
    
    // Commit
    $db->transCommit();
    
    echo "\n✅ SUCCESS! Transfer completed and inventory updated.\n";
    
} catch (\Exception $e) {
    $db->transRollback();
    echo "\n❌ ERROR: {$e->getMessage()}\n";
    exit(1);
}
