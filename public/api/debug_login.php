<?php
// Debug login issues
header('Content-Type: text/html; charset=UTF-8');

include "../config/mysql.php";

echo "<h2>Login Debugging Tool</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    echo "<h3>Testing Login:</h3>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Password Length:</strong> " . strlen($password) . " characters</p>";
    
    // Query user
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE LOWER(email) = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        echo "<p style='color:green;'>✓ User found in database</p>";
        echo "<ul>";
        echo "<li>ID: " . $user['id'] . "</li>";
        echo "<li>Username: " . htmlspecialchars($user['username']) . "</li>";
        echo "<li>Email: " . htmlspecialchars($user['email']) . "</li>";
        echo "<li>Password Hash: " . substr($user['password'], 0, 30) . "...</li>";
        echo "<li>Hash Length: " . strlen($user['password']) . "</li>";
        echo "</ul>";
        
        // Test password verification
        $isValid = password_verify($password, $user['password']);
        
        echo "<hr>";
        echo "<h3>Password Verification Result:</h3>";
        if ($isValid) {
            echo "<p style='color:green; font-size:20px;'>✓✓✓ PASSWORD IS CORRECT! ✓✓✓</p>";
            echo "<p>Login should work. If it doesn't, check the login.php code.</p>";
        } else {
            echo "<p style='color:red; font-size:20px;'>✗✗✗ PASSWORD IS INCORRECT ✗✗✗</p>";
            echo "<p><strong>Possible issues:</strong></p>";
            echo "<ul>";
            echo "<li>Wrong password entered</li>";
            echo "<li>Password has extra spaces</li>";
            echo "<li>Password was changed after registration</li>";
            echo "<li>Case sensitivity issue (unlikely with BCRYPT)</li>";
            echo "</ul>";
        }
    } else {
        echo "<p style='color:red;'>✗ User NOT found with email: " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Possible issues:</strong></p>";
        echo "<ul>";
        echo "<li>Email spelling is wrong</li>";
        echo "<li>Email case mismatch (should be handled, but check)</li>";
        echo "<li>User was not registered successfully</li>";
        echo "</ul>";
    }
    
    $stmt->close();
}

// Show form
echo "<hr>";
echo "<h3>Test Login:</h3>";
echo "<form method='POST'>";
echo "<p>Email: <input type='email' name='email' required style='padding:5px; width:300px;'></p>";
echo "<p>Password: <input type='password' name='password' required style='padding:5px; width:300px;'></p>";
echo "<button type='submit' style='padding:10px 20px;'>Test Login</button>";
echo "</form>";

// Show all users
echo "<hr>";
echo "<h3>All Registered Users:</h3>";
$result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC LIMIT 10");
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
    echo "<p>No users found.</p>";
}
?>
