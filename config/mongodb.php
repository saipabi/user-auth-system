<?php
require_once __DIR__ . '/../vendor/autoload.php';

$uri = $_ENV['MONGO_URI'] ?? null;
$dbName = $_ENV['MONGO_DB_NAME'] ?? 'profiles';

if (!$uri) {
    throw new Exception("MONGO_URI not set");
}

$client = new MongoDB\Client($uri);
$database = $client->selectDatabase($dbName);
$profileCollection = $database->selectCollection('profiles');
