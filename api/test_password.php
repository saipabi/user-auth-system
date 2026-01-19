<?php
// Test password hashing and verification
header('Content-Type: text/html; charset=UTF-8');

include "../config/mysql.php";

echo "<h2>Password Verification Test</h2>";

// Get test email from GET parameter
$testEmail = $_GET['email'] ?? '';

if ($testEmail) {
    echo "<h3>Testing email: " . htmlspecialchars($testEmail) . "</h3>";
    
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email=?");
    $stmt->bind_param("s", $testEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        echo "<p><strong>User found:</strong></p>";
        echo "<ul>";
        echo "<li>ID: " . $user['id'] . "</li>";
        echo "<li>Username: " . htmlspecialchars($user['username']) . "</li>";
        echo "<li>Email: " . htmlspecialchars($user['email']) . "</li>";
        echo "<li>Password Hash: " . substr($user['password'], 0, 30) . "...</li>";
        echo "<li>Hash Length: " . strlen($user['password']) . " characters</li>";
        echo "<li>Hash Format: " . (substr($user['password'], 0, 7) === '$2y$10' ? "BCRYPT (Correct)" : "UNKNOWN") . "</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<h3>Test Password Verification</h3>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='email' value='" . htmlspecialchars($testEmail) . "'>";
        echo "<input type='password' name='test_password' placeholder='Enter password to test' required>";
        echo "<button type='submit'>Test Password</button>";
        echo "</form>";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_password'])) {
            $testPassword = $_POST['test_password'];
            $isValid = password_verify($testPassword, $user['password']);
            
            echo "<p><strong>Test Result:</strong></p>";
            if ($isValid) {
                echo "<p style='color:green;'>✓ Password is CORRECT!</p>";
            } else {
                echo "<p style='color:red;'>✗ Password is INCORRECT</p>";
                echo "<p>Make sure you're using the exact password you registered with.</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>User not found with email: " . htmlspecialchars($testEmail) . "</p>";
    }
    
    $stmt->close();
} else {
    // Show all users
    echo "<h3>All Registered Users:</h3>";
    $result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC LIMIT 10");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th><th>Test</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td><a href='?email=" . urlencode($row['email']) . "'>Test Login</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
}
?>
