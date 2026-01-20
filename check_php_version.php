<?php
// Run this file to check your PHP version details
// Open: http://localhost/user-auth-system/check_php_version.php

echo "<h2>PHP Version Details for MongoDB Extension</h2>";
echo "<hr>";

echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Thread Safety:</strong> " . (ZEND_THREAD_SAFE ? "YES (Thread Safe - TS)" : "NO (Non-Thread Safe - NTS)") . "</p>";
echo "<p><strong>Architecture:</strong> " . (PHP_INT_SIZE == 8 ? "x64 (64-bit)" : "x86 (32-bit)") . "</p>";

echo "<hr>";
echo "<h3>Download This:</h3>";

if (ZEND_THREAD_SAFE) {
    if (PHP_INT_SIZE == 8) {
        echo "<p style='color:green; font-size:18px;'><strong>✓ Download: 8.0 Thread Safe (TS) x64</strong></p>";
    } else {
        echo "<p style='color:green; font-size:18px;'><strong>✓ Download: 8.0 Thread Safe (TS) x86</strong></p>";
    }
} else {
    if (PHP_INT_SIZE == 8) {
        echo "<p style='color:green; font-size:18px;'><strong>✓ Download: 8.0 Non Thread Safe (NTS) x64</strong></p>";
    } else {
        echo "<p style='color:green; font-size:18px;'><strong>✓ Download: 8.0 Non Thread Safe (NTS) x86</strong></p>";
    }
}

echo "<hr>";
echo "<p><small>Note: XAMPP typically uses <strong>Thread Safe (TS) x64</strong></small></p>";
?>
