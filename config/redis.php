<?php
// config/redis.php

// Ensure Composer autoloader is included if not already
require_once __DIR__ . '/../vendor/autoload.php';

$redis = null;

try {
    // Predis connection
    // It uses 127.0.0.1 and 6379 by default
    $redis = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ]);

    // Test connection
    $redis->ping();
    
} catch (Exception $e) {
    // Log the error and set $redis to null so the app doesn't crash
    error_log("Redis Connection Error: " . $e->getMessage());
    $redis = null;
}