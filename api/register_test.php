<?php
// Simple test endpoint to check registration
header('Content-Type: application/json');

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$response = [
    'status' => 'testing',
    'post_data' => $_POST,
    'server_method' => $_SERVER['REQUEST_METHOD']
];

// Test MySQL connection
try {
    include "../config/mysql.php";
    if (isset($conn) && !$conn->connect_error) {
        $response['mysql'] = 'connected';
        
        // Check if table exists
        $result = $conn->query("SHOW TABLES LIKE 'users'");
        $response['table_exists'] = ($result && $result->num_rows > 0);
        
        // Count users
        $countResult = $conn->query("SELECT COUNT(*) as count FROM users");
        if ($countResult) {
            $row = $countResult->fetch_assoc();
            $response['user_count'] = $row['count'];
        }
    } else {
        $response['mysql'] = 'not_connected';
        $response['mysql_error'] = $conn->connect_error ?? 'Connection object not set';
    }
} catch (Exception $e) {
    $response['mysql'] = 'error';
    $response['mysql_error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
