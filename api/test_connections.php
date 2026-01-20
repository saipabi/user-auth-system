<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Include the configuration files
require_once __DIR__ . '/../config/mysql.php';
require_once __DIR__ . '/../config/redis.php';
require_once __DIR__ . '/../config/mongodb.php';

// 2. Set the header to show JSON format
header('Content-Type: application/json');

// 3. Output the connection status
echo json_encode([
    "MySQL" => isset($conn) ? "Connected Successfully" : "Failed",
    "Redis" => isset($redis) ? "Connected Successfully" : "Failed",
    "MongoDB" => $mongoStatus ?? "Not Configured"
], JSON_PRETTY_PRINT);