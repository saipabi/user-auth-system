<?php
header('Content-Type: application/json');

// Include Composer's autoloader for Predis
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Include your configuration files
    // Ensure these file names match your actual files in the config folder
    include "../config/mysql.php";
    include "../config/redis.php"; 
    
    // Get and clean inputs
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Email and password are required"]);
        exit;
    }
    
    // Normalize email
    $email = strtolower(trim($email));
    
    // Basic email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Please enter a valid email address"]);
        exit;
    }
    
    // Check MySQL connection (from your mysql.php)
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }
    
    // Query user from database
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode(["error" => "Database query preparation failed"]);
        exit;
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = $result->fetch_assoc();
    
    if (!$res) {
        echo json_encode(["error" => "Invalid email or password"]);
        $stmt->close();
        exit;
    }
    
    // Verify password against hashed password in DB
    if (password_verify($password, $res["password"])) {
        $userId = $res["id"];
        $token = bin2hex(random_bytes(16));
        
        // Store token in Redis using Predis
        // Note: We use the $redis object created in your redis.php
        if (isset($redis) && $redis !== null) {
            try {
                // setex: key, seconds, value
                $redis->setex($token, 3600, $userId);
            } catch (Exception $e) {
                // Log Redis error but don't stop the user from logging in
                error_log("Redis Error: " . $e->getMessage());
            }
        } else {
            // Fallback token if Redis is down
            $token = "fb_" . base64_encode($userId . ':' . time());
        }
        
        echo json_encode([
            "status" => "success",
            "token" => $token,
            "message" => "Login successful"
        ]);
        
        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid email or password"]);
        $stmt->close();
    }
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(["error" => "Server error occurred"]);
} catch (Error $e) {
    error_log("Login fatal error: " . $e->getMessage());
    echo json_encode(["error" => "A critical server error occurred"]);
}