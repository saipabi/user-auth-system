<?php
$envPath = dirname(__DIR__) . '/.env';

// Only try to load the .env file if it actually exists (Local development)
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}
// If the file is missing (on Railway), PHP will simply use getenv() 
// to grab the variables you typed into the Railway dashboard.