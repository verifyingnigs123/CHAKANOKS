<?php
// Direct database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'chakanoks1';

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Fix Payment Transactions and Purchase Order Status ===\n\n";

// Step 1: Update payment transactions
echo "Step 1: Updating payment transactions from pending to completed...\n";
$paymentQuery = "UPDATE payment_transactions 
                 SET status = 'completed',
                     payment_date = NOW(),
                     notes = CONCAT(COALESCE(notes, ''), ' - Auto-updated to paid status')
                 WHERE status = 'pending'";

$result = $mysqli->query($paymentQuery);
$affectedPayments = $mysqli->affected_rows;
echo "✓ Updated {$affectedPayments} payment transaction(s) to completed\n\n";

// Step 2: Update purchase orders to completed
echo "Step 2: Updating purchase orders to completed status...\n";
$poQuery = "UPDATE purchase_orders po
            INNER JOIN deliveries d ON d.purchase_order_id = po.id
            SET po.status = 'completed'
            WHERE d.status = 'delivered' 
            AND po.status != 'completed'";

$result = $mysqli->query($poQuery);
$affectedPOs = $mysqli->affected_rows;
echo "✓ Updated {$affectedPOs} purchase order(s) to completed\n\n";

// Step 3: Show updated payment transactions
echo "=== Updated Payment Transactions ===\n";
$result = $mysqli->query("SELECT id, transaction_number, status, payment_date, amount, notes 
                        FROM payment_transactions 
                        ORDER BY created_at DESC 
                        LIMIT 10");
$payments = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

if (!empty($payments)) {
    foreach ($payments as $payment) {
        echo sprintf("ID: %d | %s | Status: %s | Amount: ₱%.2f | Date: %s\n",
            $payment['id'],
            $payment['transaction_number'],
            $payment['status'],
            $payment['amount'],
            $payment['payment_date'] ?? 'N/A'
        );
    }
} else {
    echo "No payment transactions found.\n";
}

echo "\n=== Updated Purchase Orders ===\n";
$result = $mysqli->query("SELECT po.id, po.po_number, po.status, po.total_amount,
                          d.delivery_number, d.status as delivery_status
                   FROM purchase_orders po
                   LEFT JOIN deliveries d ON d.purchase_order_id = po.id
                   ORDER BY po.created_at DESC
                   LIMIT 10");
$pos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

if (!empty($pos)) {
    foreach ($pos as $po) {
        echo sprintf("ID: %d | %s | Status: %s | Amount: ₱%.2f | Delivery: %s (%s)\n",
            $po['id'],
            $po['po_number'],
            $po['status'],
            $po['total_amount'],
            $po['delivery_number'] ?? 'Not scheduled',
            $po['delivery_status'] ?? 'N/A'
        );
    }
} else {
    echo "No purchase orders found.\n";
}

echo "\n=== Summary ===\n";
echo "✓ {$affectedPayments} payment(s) marked as paid\n";
echo "✓ {$affectedPOs} purchase order(s) marked as completed\n";
echo "\nAll done! Payments are now marked as paid and POs are completed.\n";

$mysqli->close();
