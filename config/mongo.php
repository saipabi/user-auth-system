<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Fetch the URI you set in Railway
$uri = getenv('MONGO_URI');

// 1. Check if the variable is missing
if (empty($uri)) {
    die("MongoDB Error: MONGO_URI is not defined in Railway Environment Variables.");
}

try {
    // 2. Initialize the client
    $client = new MongoDB\Client($uri);
    
    // 3. Force a connection test (Ping)
    $client->selectDatabase('admin')->command(['ping' => 1]);
    
} catch (Exception $e) {
    // 4. Catch Network or Authentication errors
    die("MongoDB Connection Failed: " . $e->getMessage());
}