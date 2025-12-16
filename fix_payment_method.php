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

echo "=== Fix Payment Method in Purchase Orders ===\n\n";

// Update payment_method from 'pending' to 'paypal' for all POs
$query = "UPDATE purchase_orders 
          SET payment_method = 'paypal' 
          WHERE payment_method = 'pending' OR payment_method IS NULL";

$result = $mysqli->query($query);
$affected = $mysqli->affected_rows;

echo "✓ Updated {$affected} purchase order(s) payment method to PayPal\n\n";

// Also update deliveries payment_method
$query2 = "UPDATE deliveries 
           SET payment_method = 'paypal' 
           WHERE payment_method = 'pending' OR payment_method IS NULL";

$result2 = $mysqli->query($query2);
$affected2 = $mysqli->affected_rows;

echo "✓ Updated {$affected2} delivery(ies) payment method to PayPal\n\n";

// Verify the changes
echo "=== Verification ===\n";
$result = $mysqli->query("SELECT po_number, status, payment_method, total_amount 
                          FROM purchase_orders 
                          ORDER BY created_at DESC 
                          LIMIT 10");

if ($result) {
    $pos = $result->fetch_all(MYSQLI_ASSOC);
    
    foreach ($pos as $po) {
        echo sprintf("%-20s | Status: %-10s | Payment: %-10s | Amount: ₱%.2f\n",
            $po['po_number'],
            $po['status'],
            $po['payment_method'],
            $po['total_amount']
        );
    }
}

echo "\nAll done! Payment methods updated to PayPal.\n";

$mysqli->close();
