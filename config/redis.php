<?php
require_once __DIR__ . '/../vendor/autoload.php';

$host = getenv('REDIS_HOST');
$port = getenv('REDIS_PORT');
$pass = getenv('REDIS_PASS');

$parameters = [
    'scheme'   => 'tls', 
    'host'     => $host,
    'port'     => $port,
    'password' => $pass,
];

try {
    $redis = new Predis\Client($parameters);
    // We remove the "echo" so it doesn't mess up your API responses
    $redis->connect(); 
} catch (Exception $e) {
    // Log the error instead of echoing it
    error_log("Redis Connection Failed: " . $e->getMessage());
}