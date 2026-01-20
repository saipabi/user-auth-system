<?php
require 'vendor/autoload.php';

// .env லோடு செய்தல்
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

echo "--- Final Connection Check ---\n";

// 1. MySQL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
$mysql_check = mysqli_real_connect($conn, $_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
echo ($mysql_check) ? "✅ MySQL: Success\n" : "❌ MySQL: Failed\n";

// 2. MongoDB
try {
    $mongoClient = new MongoDB\Client($_ENV['MONGO_URI']);
    $mongoClient->listDatabases();
    echo "✅ MongoDB: Success\n";
} catch (Exception $e) { echo "❌ MongoDB: Failed\n"; }

// 3. Redis (Using Predis)
try {
    $redis = new Predis\Client(); // இது தானாகவே localhost:6379-ஐ எடுத்துக்கொள்ளும்
    $redis->ping();
    echo "✅ Redis: Success\n";
} catch (Exception $e) { echo "❌ Redis: Failed\n"; }