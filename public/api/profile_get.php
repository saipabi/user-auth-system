<?php
header('Content-Type: application/json');
error_reporting(0); 
ini_set('display_errors', 0);

include "../config/redis.php";
include "../config/mongo.php";

$token = $_POST["token"] ?? '';

if (empty($token)) {
    echo json_encode(["error" => "Token is required"]);
    exit;
}

if (!isset($profileCollection) || $profileCollection === null) {
    echo json_encode(["error" => "MongoDB is not configured."]);
    exit;
}

$userId = null;

// 1. Get userId from Redis (Updated for Predis)
if (isset($redis) && $redis !== null) {
    try {
        // Predis uses the same get() method
        $userId = $redis->get($token);
    } catch (Exception $e) {
        $userId = null;
    }
}

// 2. Fallback: decode token if Redis fails
if (!$userId) {
    $decoded = @base64_decode($token);
    if ($decoded && strpos($decoded, ':') !== false) {
        $parts = explode(':', $decoded);
        $userId = intval($parts[0]);
    }
}

if (!$userId) {
    echo json_encode(["error" => "Invalid or expired token"]);
    exit;
}

// 3. Fetch from MongoDB
try {
    $data = $profileCollection->findOne(["user_id" => (int)$userId]);
    
    if (!$data) {
        echo json_encode(["age" => "", "dob" => "", "contact" => ""]);
    } else {
        echo json_encode([
            "age" => (string)($data["age"] ?? ""),
            "dob" => (string)($data["dob"] ?? ""),
            "contact" => (string)($data["contact"] ?? "")
        ]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
exit;