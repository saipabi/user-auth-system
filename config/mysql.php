<?php
$conn = mysqli_init();

$host = $_ENV['DB_HOST'] ?? null;
$port = (int)($_ENV['DB_PORT'] ?? 3306);
$user = $_ENV['DB_USER'] ?? null;
$pass = $_ENV['DB_PASS'] ?? null;
$db   = $_ENV['DB_NAME'] ?? null;

if (!$host || !$user || !$db) {
    die("MySQL environment variables missing");
}

if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    die("MySQL connection failed: " . mysqli_connect_error());
}
