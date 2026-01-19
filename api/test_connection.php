<?php
header('Content-Type: text/plain');

echo "=== CONNECTION TEST ===\n\n";

// Test MySQL
echo "1. Testing MySQL Connection...\n";
include "../config/mysql.php";
if (isset($conn) && !$conn->connect_error) {
    echo "   ✓ MySQL Connected\n";
    echo "   Database: internship_task\n";
    
    // Check if database exists
    $result = $conn->query("SELECT DATABASE()");
    if ($result) {
        $row = $result->fetch_array();
        echo "   Current DB: " . ($row[0] ?? "None") . "\n";
    }
    
    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result && $result->num_rows > 0) {
        echo "   ✓ Users table exists\n";
    } else {
        echo "   ✗ Users table NOT FOUND!\n";
        echo "   Please run database/mysql_schema.sql to create the table.\n";
    }
} else {
    echo "   ✗ MySQL Connection FAILED\n";
    if (isset($conn)) {
        echo "   Error: " . $conn->connect_error . "\n";
    }
}

echo "\n2. Testing POST Data...\n";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "   POST method detected\n";
    echo "   Username: " . ($_POST['username'] ?? 'NOT SET') . "\n";
    echo "   Email: " . ($_POST['email'] ?? 'NOT SET') . "\n";
    echo "   Password: " . (isset($_POST['password']) ? 'SET' : 'NOT SET') . "\n";
} else {
    echo "   GET method (POST expected for registration)\n";
}

echo "\n3. File Paths...\n";
echo "   Register.php: " . (file_exists(__DIR__ . "/register.php") ? "EXISTS" : "NOT FOUND") . "\n";
echo "   MySQL Config: " . (file_exists(__DIR__ . "/../config/mysql.php") ? "EXISTS" : "NOT FOUND") . "\n";

?>
