<?php
// Test file to check if register.php is accessible
echo "Testing register.php endpoint...\n";

// Simulate POST data
$_POST['username'] = 'testuser';
$_POST['email'] = 'test@test.com';
$_POST['password'] = 'testpass123';

// Check if files exist
echo "Checking files...\n";
echo "MySQL config exists: " . (file_exists("../config/mysql.php") ? "YES" : "NO") . "\n";
echo "Register.php exists: " . (file_exists("register.php") ? "YES" : "NO") . "\n";

// Try including
include "../config/mysql.php";
echo "MySQL connection: " . ($conn ? "OK" : "FAILED") . "\n";

if ($conn->connect_error) {
    echo "MySQL Error: " . $conn->connect_error . "\n";
} else {
    echo "MySQL Connected successfully\n";
}

?>
