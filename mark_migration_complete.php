<?php
// Mark migrations as complete
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'vendor/autoload.php';

// Bootstrap CodeIgniter
$app = require 'app/Config/Paths.php';
require FCPATH . '../system/bootstrap.php';

$db = \Config\Database::connect();

// Mark tracking fields migration as complete
$db->table('migrations')->insert([
    'version' => '2025-12-16-000001',
    'class' => 'App\Database\Migrations\AddTrackingFieldsToTransfers',
    'group' => 'default',
    'namespace' => 'App',
    'time' => time(),
    'batch' => 4
]);

echo "Tracking fields migration marked as complete\n";

// Now run the driver migration
$db->query("ALTER TABLE transfers 
    ADD COLUMN driver_id INT UNSIGNED NULL AFTER dispatched_at,
    ADD COLUMN driver_name VARCHAR(255) NULL AFTER driver_id,
    ADD COLUMN driver_phone VARCHAR(50) NULL AFTER driver_name,
    ADD COLUMN vehicle_info VARCHAR(255) NULL AFTER driver_phone
");

echo "Driver fields added to transfers table\n";

// Mark driver migration as complete
$db->table('migrations')->insert([
    'version' => '2025-12-16-100001',
    'class' => 'App\Database\Migrations\AddDriverToTransfers',
    'group' => 'default',
    'namespace' => 'App',
    'time' => time(),
    'batch' => 4
]);

echo "Driver migration marked as complete\n";
echo "All migrations completed successfully!\n";
