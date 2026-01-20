<?php
require_once __DIR__ . '/../vendor/autoload.php';

$host = $_ENV['REDISHOST'] ?? null;
$port = $_ENV['REDISPORT'] ?? 6379;
$pass = $_ENV['REDISPASSWORD'] ?? null;

$redis = null;

if ($host) {
    try {
        $redis = new Predis\Client([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
            'password' => $pass
        ]);
        $redis->connect();
    } catch (Exception $e) {
        error_log("Redis error: " . $e->getMessage());
        $redis = null;
    }
}
