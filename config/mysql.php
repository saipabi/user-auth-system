<?php
require_once __DIR__ . '/env.php';

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

$success = mysqli_real_connect(
    $conn,
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME'),
    (int) getenv('DB_PORT')
);

if (!$success) {
    die("Connection failed: " . mysqli_connect_error());
}
