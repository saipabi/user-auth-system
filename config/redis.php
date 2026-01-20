<?php
require_once __DIR__ . '/../vendor/autoload.php';

$host = $_ENV['REDIS_HOST'] ?? null;
$port = $_ENV['REDIS_PORT'] ?? 6379;
$pass = $_ENV['REDIS_PASS'] ?? null;

$redis = null;

if ($host) {
    try {
        $redis = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => $host,
            'port'     => $port,
            'password' => $pass
        ]);
        $redis->connect();
    } catch (Exception $e) {
        error_log("Redis error: " . $e->getMessage());
        $redis = null;
    }
}
