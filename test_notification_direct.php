<?php

// Direct database test for notifications
$host = 'localhost';
$dbname = 'chakanoks1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected\n\n";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage() . "\n");
}

// Test 1: Insert a notification directly
echo "Test 1: Creating a test notification directly in database...\n";
try {
    $stmt = $pdo->prepare("
        INSERT INTO notifications (user_id, type, title, message, link, is_read, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        1, // Central Admin user ID
        'info',
        '🧪 Direct Test Notification',
        'This notification was created directly via SQL to test the database.',
        '/transfers',
        0,
        date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        echo "✅ Direct notification created successfully!\n";
        echo "   Notification ID: " . $pdo->lastInsertId() . "\n\n";
    } else {
        echo "❌ Failed to create direct notification\n\n";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Check all notifications for Central Admin
echo "Test 2: Checking all notifications for Central Admin (user_id=1)...\n";
$stmt = $pdo->query("SELECT * FROM notifications WHERE user_id = 1 ORDER BY created_at DESC LIMIT 10");
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($notifications)) {
    echo "❌ No notifications found\n\n";
} else {
    echo "✅ Found " . count($notifications) . " notification(s):\n";
    foreach ($notifications as $notif) {
        echo "   - [{$notif['type']}] {$notif['title']}\n";
        echo "     Message: {$notif['message']}\n";
        echo "     Created: {$notif['created_at']}, Read: " . ($notif['is_read'] ? 'Yes' : 'No') . "\n\n";
    }
}

// Test 3: Check the notifications table structure
echo "Test 3: Checking notifications table structure...\n";
$stmt = $pdo->query("DESCRIBE notifications");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Columns in notifications table:\n";
foreach ($columns as $col) {
    echo "   - {$col['Field']} ({$col['Type']}) " . ($col['Null'] == 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
