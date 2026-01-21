<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$uri = $_ENV['MONGO_URI'] ?? null;

if (!$uri) {
    echo "❌ MONGO_URI not set";
    exit;
}

try {
    $client = new MongoDB\Client($uri);
    $client->listDatabases();
    echo "✅ MongoDB CONNECTED SUCCESSFULLY";
} catch (Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage();
}
