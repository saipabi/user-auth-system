<?php
$conn = mysqli_init();

$host = $_ENV['MYSQLHOST'] ?? null;
$port = (int)($_ENV['MYSQLPORT'] ?? 3306);
$user = $_ENV['MYSQLUSER'] ?? null;
$pass = $_ENV['MYSQLPASSWORD'] ?? null;
$db   = $_ENV['MYSQLDATABASE'] ?? null;

if (!$host || !$user || !$db) {
    die("MySQL environment variables missing");
}

if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    die("MySQL connection failed: " . mysqli_connect_error());
}
