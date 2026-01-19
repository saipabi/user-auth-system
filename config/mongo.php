<?php
$profileCollection = null;

// Composer autoloader 
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// .env லோடு 
if (class_exists('Dotenv\\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

if (!class_exists('MongoDB\\Client')) {
    $profileCollection = null;
    return;
}

try {
    // .env-from URI and DB Name 
    $uri = $_ENV['MONGO_URI'] ?? "mongodb://127.0.0.1:27017";
    $dbName = $_ENV['MONGO_DB_NAME'] ?? "internship_task";

    $client = new MongoDB\Client($uri);
    $mongoDB = $client->$dbName;
    $profileCollection = $mongoDB->profiles;

    //  (Testing purposes only)
    // echo "MongoDB Connected Successfully!";
} catch (Exception $e) {
    $profileCollection = null;
    return;
}