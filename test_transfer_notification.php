<?php

// Simple database connection test
$host = 'localhost';
$dbname = 'chakanoks1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

echo "=== TESTING TRANSFER NOTIFICATIONS ===\n\n";

// 1. Check for Central Admin users
echo "1. Checking for Central Admin users:\n";
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'central_admin' AND status = 'active'");
$centralAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($centralAdmins)) {
    echo "   ❌ NO CENTRAL ADMIN USERS FOUND!\n";
    echo "   This is why notifications aren't being sent.\n\n";
} else {
    echo "   ✅ Found " . count($centralAdmins) . " Central Admin user(s):\n";
    foreach ($centralAdmins as $admin) {
        echo "      - ID: {$admin['id']}, Username: {$admin['username']}, Email: {$admin['email']}\n";
    }
    echo "\n";
}

// 2. Check recent transfers
echo "2. Checking recent transfers:\n";
$stmt = $pdo->query("SELECT * FROM transfers ORDER BY created_at DESC LIMIT 5");
$transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($transfers)) {
    echo "   ❌ No transfers found\n\n";
} else {
    echo "   ✅ Found " . count($transfers) . " recent transfer(s):\n";
    foreach ($transfers as $transfer) {
        echo "      - Transfer #{$transfer['transfer_number']}, Status: {$transfer['status']}, Created: {$transfer['created_at']}\n";
    }
    echo "\n";
}

// 3. Check notifications for Central Admin
echo "3. Checking notifications for Central Admin:\n";
if (!empty($centralAdmins)) {
    foreach ($centralAdmins as $admin) {
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND title LIKE '%Transfer%' ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$admin['id']]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "   User: {$admin['username']} (ID: {$admin['id']})\n";
        if (empty($notifications)) {
            echo "      ❌ No transfer notifications found\n";
        } else {
            echo "      ✅ Found " . count($notifications) . " notification(s):\n";
            foreach ($notifications as $notif) {
                echo "         - {$notif['title']}: {$notif['message']} (Created: {$notif['created_at']})\n";
            }
        }
        echo "\n";
    }
}

// 4. Check all users and their roles
echo "4. Checking all users and their roles:\n";
$stmt = $pdo->query("SELECT id, username, email, role, status FROM users ORDER BY role, username");
$allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$roleGroups = [];
foreach ($allUsers as $user) {
    $roleGroups[$user['role']][] = $user;
}

foreach ($roleGroups as $role => $users) {
    echo "   Role: $role (" . count($users) . " user(s))\n";
    foreach ($users as $user) {
        echo "      - {$user['username']} (ID: {$user['id']}, Status: {$user['status']})\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
