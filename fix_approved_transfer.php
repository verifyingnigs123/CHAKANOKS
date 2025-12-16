<?php
/**
 * Fix Script: Complete Approved Transfer and Update Inventory
 * 
 * This script will:
 * 1. Find the approved transfer (TRF20251215001)
 * 2. Deduct inventory from source branch (Main Branch)
 * 3. Add inventory to destination branch (Mansor Malic Franchise)
 * 4. Update transfer status to completed
 */

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = require_once FCPATH . '../app/Config/Paths.php';
$app = new \CodeIgniter\CodeIgniter($app);
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

// Get the transfer
$transferNumber = 'TRF20251215001'; // Update this if needed
$transfer = $db->table('transfers')
    ->where('transfer_number', $transferNumber)
    ->get()
    ->getRowArray();

if (!$transfer) {
    echo "Transfer {$transferNumber} not found!\n";
    exit(1);
}

echo "Found transfer: {$transfer['transfer_number']}\n";
echo "Status: {$transfer['status']}\n";
echo "From Branch ID: {$transfer['from_branch_id']}\n";
echo "To Branch ID: {$transfer['to_branch_id']}\n\n";

// Get transfer items
$items = $db->table('transfer_items')
    ->select('transfer_items.*, products.name as product_name')
    ->join('products', 'products.id = transfer_items.product_id')
    ->where('transfer_id', $transfer['id'])
    ->get()
    ->getResultArray();

echo "Transfer Items:\n";
foreach ($items as $item) {
    echo "  - {$item['product_name']}: {$item['quantity']} units\n";
}
echo "\n";

// Start transaction
$db->transStart();

try {
    // Step 1: Deduct from source branch
    echo "Step 1: Deducting inventory from source branch (ID: {$transfer['from_branch_id']})\n";
    foreach ($items as $item) {
        $fromInventory = $db->table('inventory')
            ->where('branch_id', $transfer['from_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();

        if ($fromInventory) {
            $oldQty = $fromInventory['quantity'];
            $newQty = $oldQty - $item['quantity'];
            
            $db->table('inventory')
                ->where('id', $fromInventory['id'])
                ->update([
                    'quantity' => $newQty,
                    'available_quantity' => $newQty - ($fromInventory['reserved_quantity'] ?? 0),
                    'last_updated_at' => date('Y-m-d H:i:s')
                ]);
            
            echo "  ✓ {$item['product_name']}: {$oldQty} → {$newQty}\n";
        } else {
            echo "  ✗ {$item['product_name']}: No inventory record found!\n";
        }
    }
    echo "\n";

    // Step 2: Add to destination branch
    echo "Step 2: Adding inventory to destination branch (ID: {$transfer['to_branch_id']})\n";
    foreach ($items as $item) {
        $toInventory = $db->table('inventory')
            ->where('branch_id', $transfer['to_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();

        if ($toInventory) {
            $oldQty = $toInventory['quantity'];
            $newQty = $oldQty + $item['quantity'];
            
            $db->table('inventory')
                ->where('id', $toInventory['id'])
                ->update([
                    'quantity' => $newQty,
                    'available_quantity' => $newQty - ($toInventory['reserved_quantity'] ?? 0),
                    'last_updated_at' => date('Y-m-d H:i:s')
                ]);
            
            echo "  ✓ {$item['product_name']}: {$oldQty} → {$newQty}\n";
        } else {
            // Create new inventory record
            $db->table('inventory')->insert([
                'branch_id' => $transfer['to_branch_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'reserved_quantity' => 0,
                'available_quantity' => $item['quantity'],
                'last_updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            echo "  ✓ {$item['product_name']}: 0 → {$item['quantity']} (new record)\n";
        }
        
        // Update received quantity
        $db->table('transfer_items')
            ->where('id', $item['id'])
            ->update(['quantity_received' => $item['quantity']]);
    }
    echo "\n";

    // Step 3: Update transfer status
    echo "Step 3: Updating transfer status to completed\n";
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
    
    echo "  ✓ Transfer status updated to completed\n\n";

    // Commit transaction
    $db->transComplete();

    if ($db->transStatus() === false) {
        echo "❌ Transaction failed! Rolling back...\n";
        exit(1);
    }

    echo "✅ SUCCESS! Transfer completed and inventory updated.\n\n";
    
    // Show final inventory
    echo "Final Inventory:\n";
    echo "Source Branch (ID: {$transfer['from_branch_id']}):\n";
    foreach ($items as $item) {
        $inv = $db->table('inventory')
            ->where('branch_id', $transfer['from_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();
        echo "  - {$item['product_name']}: {$inv['quantity']} units\n";
    }
    
    echo "\nDestination Branch (ID: {$transfer['to_branch_id']}):\n";
    foreach ($items as $item) {
        $inv = $db->table('inventory')
            ->where('branch_id', $transfer['to_branch_id'])
            ->where('product_id', $item['product_id'])
            ->get()
            ->getRowArray();
        echo "  - {$item['product_name']}: " . ($inv ? $inv['quantity'] : 0) . " units\n";
    }

} catch (\Exception $e) {
    $db->transRollback();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
