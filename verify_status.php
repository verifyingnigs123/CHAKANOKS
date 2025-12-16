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

echo "=== Current Purchase Orders Status ===\n\n";

$result = $mysqli->query("SELECT po.id, po.po_number, po.status, po.payment_method, po.total_amount,
                          d.delivery_number, d.status as delivery_status,
                          pt.transaction_number, pt.status as payment_status, pt.payment_date
                   FROM purchase_orders po
                   LEFT JOIN deliveries d ON d.purchase_order_id = po.id
                   LEFT JOIN payment_transactions pt ON pt.purchase_order_id = po.id
                   ORDER BY po.created_at DESC");

if ($result) {
    $pos = $result->fetch_all(MYSQLI_ASSOC);
    
    foreach ($pos as $po) {
        echo "PO: {$po['po_number']}\n";
        echo "  Status: {$po['status']}\n";
        echo "  Amount: ₱" . number_format($po['total_amount'], 2) . "\n";
        echo "  Payment Method: " . ($po['payment_method'] ?? 'N/A') . "\n";
        echo "  Delivery: " . ($po['delivery_number'] ?? 'Not scheduled') . " (" . ($po['delivery_status'] ?? 'N/A') . ")\n";
        echo "  Payment: " . ($po['transaction_number'] ?? 'No transaction') . " - " . ($po['payment_status'] ?? 'N/A');
        if ($po['payment_date']) {
            echo " (Paid: {$po['payment_date']})";
        }
        echo "\n\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}

$mysqli->close();
