<?php
require 'vendor/autoload.php';

$db = \Config\Database::connect();
$fields = $db->getFieldNames('transfers');

echo "Transfers table columns:\n";
print_r($fields);
