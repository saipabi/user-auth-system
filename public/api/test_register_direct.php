<?php
// Direct test of registration without AJAX
header('Content-Type: text/html; charset=UTF-8');

echo "<h2>Direct Registration Test</h2>";

// Simulate POST data
$_POST['username'] = 'testuser_' . time();
$_POST['email'] = 'test_' . time() . '@test.com';
$_POST['password'] = 'test123';

echo "<p>Testing with:</p>";
echo "<ul>";
echo "<li>Username: " . $_POST['username'] . "</li>";
echo "<li>Email: " . $_POST['email'] . "</li>";
echo "<li>Password: " . $_POST['password'] . "</li>";
echo "</ul>";

echo "<hr>";

// Include and test register.php logic
ob_start();
include "register.php";
$output = ob_get_clean();

echo "<h3>Register.php Output:</h3>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

echo "<hr>";

// Check database
include "../config/mysql.php";
if ($conn && !$conn->connect_error) {
    echo "<h3>Database Check:</h3>";
    $result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC LIMIT 5");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found in database.</p>";
    }
} else {
    echo "<p style='color:red;'>Database connection failed!</p>";
}
?>
