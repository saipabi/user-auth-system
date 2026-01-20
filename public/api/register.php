<?php
@ob_clean();

header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "INVALID_METHOD"]);
    exit;
}

require_once __DIR__ . "/../config/mysql.php";

try {
    // Get and clean inputs
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate inputs
    if ($username === '' || $email === '' || $password === '') {
        echo json_encode(["status" => "error", "message" => "EMPTY_FIELDS"]);
        exit;
    }

    // Normalize email to lowercase for consistency
    $email = strtolower($email);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "INVALID_EMAIL"]);
        exit;
    }

    // Validate password length
    if (strlen($password) < 3) {
        echo json_encode(["status" => "error", "message" => "PASSWORD_TOO_SHORT"]);
        exit;
    }

    // Hash password with BCRYPT
    $hash = password_hash($password, PASSWORD_BCRYPT);
    if (!$hash) {
        echo json_encode(["status" => "error", "message" => "HASH_FAILED"]);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "PREPARE_FAILED"]);
        exit;
    }

    $stmt->bind_param("sss", $username, $email, $hash);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        if ($conn->errno === 1062) {
            echo json_encode(["status" => "error", "message" => "EMAIL_ALREADY_EXISTS"]);
        } else {
            echo json_encode(["status" => "error", "message" => "INSERT_FAILED"]);
        }
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "SERVER_ERROR"]);
}
