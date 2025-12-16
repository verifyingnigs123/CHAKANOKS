<?php
// Test supplier account validation

// Test cases
$testCases = [
    // Valid cases
    ['username' => 'beefsupplier', 'password' => 'BeefSupp123', 'expected' => 'PASS'],
    ['username' => 'BeefSupp', 'password' => 'Password123', 'expected' => 'PASS'],
    ['username' => 'supplier123', 'password' => 'MyPass2024', 'expected' => 'PASS'],
    
    // Invalid cases
    ['username' => 'beef_supplier', 'password' => 'BeefSupp123', 'expected' => 'FAIL - underscore in username'],
    ['username' => 'beef supplier', 'password' => 'BeefSupp123', 'expected' => 'FAIL - space in username'],
    ['username' => 'beef@supplier', 'password' => 'BeefSupp123', 'expected' => 'FAIL - special char in username'],
    ['username' => 'beefsupplier', 'password' => 'beef123', 'expected' => 'FAIL - no uppercase'],
    ['username' => 'beefsupplier', 'password' => 'BEEF123', 'expected' => 'FAIL - no lowercase'],
    ['username' => 'beefsupplier', 'password' => 'BeefSupp', 'expected' => 'FAIL - no number'],
    ['username' => 'beefsupplier', 'password' => 'Beef123!', 'expected' => 'FAIL - special char in password'],
    ['username' => 'be', 'password' => 'BeefSupp123', 'expected' => 'FAIL - username too short'],
];

echo "Supplier Account Validation Test\n";
echo "=================================\n\n";

foreach ($testCases as $i => $test) {
    echo "Test " . ($i + 1) . ": {$test['expected']}\n";
    echo "  Username: {$test['username']}\n";
    echo "  Password: {$test['password']}\n";
    
    // Validate username
    $usernameValid = preg_match('/^[a-zA-Z0-9]+$/', $test['username']) && 
                     strlen($test['username']) >= 3 && 
                     strlen($test['username']) <= 50;
    
    // Validate password
    $passwordValid = strlen($test['password']) >= 8 &&
                     preg_match('/[A-Z]/', $test['password']) &&
                     preg_match('/[a-z]/', $test['password']) &&
                     preg_match('/[0-9]/', $test['password']) &&
                     preg_match('/^[a-zA-Z0-9]+$/', $test['password']);
    
    $result = ($usernameValid && $passwordValid) ? 'PASS' : 'FAIL';
    $reasons = [];
    
    if (!$usernameValid) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $test['username'])) {
            $reasons[] = 'Username has invalid characters';
        }
        if (strlen($test['username']) < 3) {
            $reasons[] = 'Username too short';
        }
    }
    
    if (!$passwordValid) {
        if (strlen($test['password']) < 8) {
            $reasons[] = 'Password too short';
        }
        if (!preg_match('/[A-Z]/', $test['password'])) {
            $reasons[] = 'Password missing uppercase';
        }
        if (!preg_match('/[a-z]/', $test['password'])) {
            $reasons[] = 'Password missing lowercase';
        }
        if (!preg_match('/[0-9]/', $test['password'])) {
            $reasons[] = 'Password missing number';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $test['password'])) {
            $reasons[] = 'Password has special characters';
        }
    }
    
    echo "  Result: $result";
    if (!empty($reasons)) {
        echo " (" . implode(', ', $reasons) . ")";
    }
    echo "\n\n";
}

echo "\nValid Example Credentials:\n";
echo "==========================\n";
echo "Username: beefsupplier\n";
echo "Password: BeefSupp123\n";
echo "\nOR\n\n";
echo "Username: BeefSupp\n";
echo "Password: MyPass2024\n";
