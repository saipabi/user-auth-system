<?php
require_once __DIR__ . '/../vendor/autoload.php';

$uri = getenv('MONGO_URI');
$dbName = getenv('MONGO_DB_NAME');

try {
    if (!$uri) {
        throw new Exception("MONGO_URI is missing in Railway Variables!");
    }
    $client = new MongoDB\Client($uri);
    // Ping the database to confirm it is alive
    $client->selectDatabase('admin')->command(['ping' => 1]);
    $database = $client->selectDatabase($dbName);
    $mongoStatus = "Connected Successfully";
} catch (Exception $e) {
    $mongoStatus = "Error: " . $e->getMessage();
}