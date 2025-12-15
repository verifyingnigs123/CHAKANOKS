<?php
$pdo = new PDO('mysql:host=localhost;dbname=chakanoks1', 'root', '');
$stmt = $pdo->query('DESCRIBE transfers');
echo "Transfers table columns:\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}
