<?php
require_once __DIR__ . '/../config/mysql.php';
require_once __DIR__ . '/../config/redis.php';
require_once __DIR__ . '/../config/mongodb.php';

header('Content-Type: application/json');

echo json_encode([
    "MySQL" => isset($conn) ? "Connected Successfully" : "Failed",
    "Redis" => isset($redis) ? "Connected Successfully" : "Failed",
    "MongoDB" => $mongoStatus ?? "Not Configured"
], JSON_PRETTY_PRINT);