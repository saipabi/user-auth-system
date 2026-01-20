<?php
require_once __DIR__ . '/../vendor/autoload.php';

$uri = getenv('MONGO_URI');
$dbName = getenv('MONGO_DB_NAME');

try {
    if (!$uri) {
        throw new Exception("MONGO_URI variable is missing in Railway!");
    }
    $client = new MongoDB\Client($uri);
    // This line tests if the connection is actually alive
    $client->selectDatabase('admin')->command(['ping' => 1]);
    $database = $client->selectDatabase($dbName);
    $mongoStatus = "Connected Successfully";
} catch (Exception $e) {
    $mongoStatus = "Error: " . $e->getMessage();
}