<?php

// Bootstrap CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

$pathsConfig = FCPATH . '../app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get services
$notificationService = new \App\Libraries\NotificationService();

echo "=== TESTING TRANSFER NOTIFICATION CREATION ===\n\n";

// Test parameters (simulating a transfer)
$transferId = 999;
$transferNumber = "TRF-TEST-001";
$fromBranchId = 1;
$fromBranchName = "Main Branch";
$toBranchId = 2;
$toBranchName = "Branch 2";

echo "Creating notifications for test transfer:\n";
echo "  Transfer: $transferNumber (ID: $transferId)\n";
echo "  From: $fromBranchName (ID: $fromBranchId)\n";
echo "  To: $toBranchName (ID: $toBranchId)\n\n";

// Call the notification method
$count = $notificationService->notifyTransferCreatedWorkflow(
    $transferId,
    $transferNumber,
    $fromBranchId,
    $fromBranchName,
    $toBranchId,
    $toBranchName
);

echo "Result: $count notifications created\n\n";

// Check the database
$db = \Config\Database::connect();
$notifications = $db->table('notifications')
    ->where('link', "transfers/view/$transferId")
    ->orderBy('created_at', 'DESC')
    ->get()
    ->getResultArray();

echo "Notifications in database for this transfer:\n";
if (empty($notifications)) {
    echo "  ❌ No notifications found\n";
} else {
    echo "  ✅ Found " . count($notifications) . " notification(s):\n";
    foreach ($notifications as $notif) {
        $user = $db->table('users')->where('id', $notif['user_id'])->get()->getRowArray();
        $username = $user ? $user['username'] : 'Unknown';
        echo "    - User: $username (ID: {$notif['user_id']})\n";
        echo "      Title: {$notif['title']}\n";
        echo "      Message: {$notif['message']}\n";
        echo "      Created: {$notif['created_at']}\n\n";
    }
}

echo "=== TEST COMPLETE ===\n";
echo "Check writable/logs/log-" . date('Y-m-d') . ".log for detailed logs\n";
