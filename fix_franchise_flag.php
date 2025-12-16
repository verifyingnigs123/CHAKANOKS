<?php
// Fix franchise flag for Mansor Malik branch

// Bootstrap CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require_once __DIR__ . '/vendor/autoload.php';

// Load environment
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get database connection
$db = \Config\Database::connect();

// Check current branches
echo "Current branches:\n";
$branches = $db->query("SELECT id, name, is_franchise FROM branches")->getResultArray();
foreach ($branches as $branch) {
    echo "ID: {$branch['id']}, Name: {$branch['name']}, is_franchise: {$branch['is_franchise']}\n";
}

// Find Mansor Malik branch
$mansorBranch = $db->query("SELECT id, name, is_franchise FROM branches WHERE name LIKE '%Mansor%' OR name LIKE '%Malik%'")->getRowArray();

if ($mansorBranch) {
    echo "\nFound Mansor Malik branch:\n";
    echo "ID: {$mansorBranch['id']}, Name: {$mansorBranch['name']}, is_franchise: {$mansorBranch['is_franchise']}\n";
    
    if ($mansorBranch['is_franchise'] != 1) {
        echo "\nUpdating is_franchise to 1...\n";
        $db->query("UPDATE branches SET is_franchise = 1 WHERE id = ?", [$mansorBranch['id']]);
        echo "Updated successfully!\n";
    } else {
        echo "\nAlready marked as franchise.\n";
    }
} else {
    echo "\nMansor Malik branch not found.\n";
}

echo "\nFinal branches:\n";
$branches = $db->query("SELECT id, name, is_franchise FROM branches")->getResultArray();
foreach ($branches as $branch) {
    echo "ID: {$branch['id']}, Name: {$branch['name']}, is_franchise: {$branch['is_franchise']}\n";
}
