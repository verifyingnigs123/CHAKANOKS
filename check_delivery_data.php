<?php
/**
 * Check Delivery Data
 * Quick script to see what's in the database for a delivery
 */

require_once __DIR__ . '/vendor/autoload.php';

$pathsConfig = new Config\Paths();
require_once SYSTEMPATH . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

// Get delivery ID from command line or use default
$deliveryId = $argv[1] ?? 6;

echo "=== Checking Delivery ID: {$deliveryId} ===\n\n";

// Get delivery
$delivery = $db->table('deliveries')->where('id', $deliveryId)->get()->getRowArray();
if (!$delivery) {
    echo "ERROR: Delivery not found\n";
    exit(1);
}

echo "Delivery Number: {$delivery['delivery_number']}\n";
echo "Status: {$delivery['status']}\n";
echo "PO ID: {$delivery['purchase_order_id']}\n\n";

// Get PO
$po = $db->table('purchase_orders')->where('id', $delivery['purchase_order_id'])->get()->getRowArray();
echo "PO Number: {$po['po_number']}\n";
echo "Supplier ID: {$po['supplier_id']}\n";
echo "Branch ID: {$po['branch_id']}\n\n";

// Get PO Items
echo "=== PO Items ===\n";
$poItems = $db->table('purchase_order_items')
    ->where('purchase_order_id', $po['id'])
    ->get()->getResultArray();

if (empty($poItems)) {
    echo "ERROR: No PO items found!\n\n";
} else {
    foreach ($poItems as $item) {
        echo "\nItem ID: {$item['id']}\n";
        echo "  Product ID: " . ($item['product_id'] ?? 'NULL') . "\n";
        echo "  Supplier Product ID: " . ($item['supplier_product_id'] ?? 'NULL') . "\n";
        echo "  Product Name: " . ($item['product_name'] ?? 'NULL') . "\n";
        echo "  SKU: " . ($item['product_sku'] ?? 'NULL') . "\n";
        echo "  Quantity: {$item['quantity']}\n";
        
        // If has product_id, check if product exists
        if (!empty($item['product_id'])) {
            $product = $db->table('products')->where('id', $item['product_id'])->get()->getRowArray();
            if ($product) {
                echo "  ✓ Product exists: {$product['name']}\n";
            } else {
                echo "  ✗ Product NOT found in products table!\n";
            }
        }
        
        // If has supplier_product_id, check if it exists
        if (!empty($item['supplier_product_id'])) {
            $supplierProduct = $db->table('supplier_products')->where('id', $item['supplier_product_id'])->get()->getRowArray();
            if ($supplierProduct) {
                echo "  ✓ Supplier Product exists: {$supplierProduct['name']}\n";
                echo "    Mapped Product ID: " . ($supplierProduct['product_id'] ?? 'NULL') . "\n";
            } else {
                echo "  ✗ Supplier Product NOT found!\n";
            }
        }
    }
}

// Check payment
echo "\n=== Payment Transaction ===\n";
$payment = $db->table('payment_transactions')->where('purchase_order_id', $po['id'])->get()->getRowArray();
if ($payment) {
    echo "Transaction Number: {$payment['transaction_number']}\n";
    echo "Status: {$payment['status']}\n";
    echo "Amount: ₱" . number_format($payment['amount'], 2) . "\n";
} else {
    echo "No payment transaction found\n";
}

// Check inventory history
echo "\n=== Inventory History ===\n";
$history = $db->table('inventory_history')->where('delivery_id', $deliveryId)->get()->getResultArray();
if (empty($history)) {
    echo "No inventory history records (delivery not received yet)\n";
} else {
    foreach ($history as $h) {
        echo "Product ID: {$h['product_id']}, Quantity Added: {$h['quantity_added']}\n";
    }
}

echo "\n=== Complete ===\n";
