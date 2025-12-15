<?php
/**
 * Fix Missing Inventory Updates
 * 
 * This script finds deliveries that were marked as "delivered" but didn't update inventory,
 * and manually updates the inventory for them.
 * 
 * Run from command line: php fix_missing_inventory.php
 * Or access via browser: http://your-site.com/fix_missing_inventory.php (if placed in public folder)
 */

// Load CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

$pathsConfig = new Config\Paths();
require_once SYSTEMPATH . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\InventoryModel;
use App\Models\InventoryHistoryModel;
use App\Models\PaymentTransactionModel;

echo "=== Fix Missing Inventory Updates ===\n\n";

$deliveryModel = new DeliveryModel();
$purchaseOrderModel = new PurchaseOrderModel();
$purchaseOrderItemModel = new PurchaseOrderItemModel();
$inventoryModel = new InventoryModel();
$inventoryHistoryModel = new InventoryHistoryModel();
$paymentTransactionModel = new PaymentTransactionModel();

// Find all delivered deliveries
$deliveries = $deliveryModel->where('status', 'delivered')->findAll();

echo "Found " . count($deliveries) . " delivered deliveries\n\n";

$fixed = 0;
$skipped = 0;
$errors = 0;

foreach ($deliveries as $delivery) {
    echo "Checking Delivery: {$delivery['delivery_number']} (ID: {$delivery['id']})\n";
    
    // Check if inventory was updated
    $historyCount = $inventoryHistoryModel->where('delivery_id', $delivery['id'])->countAllResults();
    
    if ($historyCount > 0) {
        echo "  ✓ Inventory already updated ({$historyCount} records)\n";
        $skipped++;
        continue;
    }
    
    echo "  ⚠ Inventory NOT updated - Fixing...\n";
    
    // Get PO and items
    $po = $purchaseOrderModel->find($delivery['purchase_order_id']);
    if (!$po) {
        echo "  ✗ ERROR: Purchase Order not found\n";
        $errors++;
        continue;
    }
    
    $poItems = $purchaseOrderItemModel->where('purchase_order_id', $po['id'])->findAll();
    if (empty($poItems)) {
        echo "  ✗ ERROR: No PO items found\n";
        $errors++;
        continue;
    }
    
    $branchId = $po['branch_id'];
    echo "  Branch ID: {$branchId}\n";
    echo "  Processing " . count($poItems) . " items...\n";
    
    $db = \Config\Database::connect();
    $db->transStart();
    
    try {
        foreach ($poItems as $item) {
            $productId = $item['product_id'];
            $quantity = (int) $item['quantity'];
            
            if (!$productId || $quantity <= 0) {
                continue;
            }
            
            echo "    - Product ID {$productId}: Adding {$quantity} units\n";
            
            // Get current inventory
            $inventory = $inventoryModel->where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->first();
            
            $previousQuantity = $inventory ? $inventory['quantity'] : 0;
            $newQuantity = $previousQuantity + $quantity;
            
            // Update inventory
            $inventoryModel->updateQuantity($branchId, $productId, $newQuantity, 1);
            
            // Record history
            $inventoryHistoryModel->insert([
                'branch_id' => $branchId,
                'product_id' => $productId,
                'purchase_order_id' => $po['id'],
                'delivery_id' => $delivery['id'],
                'po_number' => $po['po_number'],
                'delivery_number' => $delivery['delivery_number'],
                'quantity_added' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'transaction_type' => 'delivery_received',
                'payment_method' => $po['payment_method'] ?? 'paypal',
                'received_by' => 1, // System user
                'notes' => "Fixed missing inventory update for Delivery {$delivery['delivery_number']}",
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Update PO item received quantity
            $purchaseOrderItemModel->update($item['id'], [
                'quantity_received' => $quantity
            ]);
            
            echo "      ✓ Updated: {$previousQuantity} → {$newQuantity}\n";
        }
        
        // Update PO status
        $purchaseOrderModel->update($po['id'], ['status' => 'completed']);
        
        // Create payment transaction if doesn't exist
        $existingPayment = $paymentTransactionModel->getByPurchaseOrder($po['id']);
        if (!$existingPayment) {
            $transactionNumber = $paymentTransactionModel->generateTransactionNumber();
            $paymentTransactionModel->insert([
                'transaction_number' => $transactionNumber,
                'purchase_order_id' => $po['id'],
                'delivery_id' => $delivery['id'],
                'branch_id' => $branchId,
                'supplier_id' => $po['supplier_id'],
                'payment_method' => 'paypal',
                'amount' => $po['total_amount'],
                'status' => 'pending',
                'payment_date' => null,
                'processed_by' => null,
                'notes' => "Payment pending for PO {$po['po_number']} - Fixed missing inventory",
            ]);
            echo "  ✓ Created payment transaction\n";
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            echo "  ✗ ERROR: Transaction failed\n";
            $errors++;
        } else {
            echo "  ✅ FIXED successfully!\n";
            $fixed++;
        }
        
    } catch (\Exception $e) {
        echo "  ✗ ERROR: " . $e->getMessage() . "\n";
        $errors++;
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "Total Deliveries: " . count($deliveries) . "\n";
echo "Already Updated: {$skipped}\n";
echo "Fixed: {$fixed}\n";
echo "Errors: {$errors}\n";

if ($fixed > 0) {
    echo "\n✅ Successfully fixed {$fixed} delivery(ies)!\n";
    echo "Please check your inventory page to verify the updates.\n";
}

if ($errors > 0) {
    echo "\n⚠ {$errors} error(s) occurred. Please check the output above for details.\n";
}

echo "\n=== Complete ===\n";
