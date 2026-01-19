<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Login Test Result</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $test_email = $_POST['test_email'] ?? '';
        $test_password = $_POST['test_password'] ?? '';
        
        if (empty($test_email) || empty($test_password)) {
            echo "<p class='error'>Please provide both email and password</p>";
        } else {
            include "../config/mysql.php";
            include "../config/redis.php";
            
            // Simulate login.php logic
            $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email=?");
            $stmt->bind_param("s", $test_email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            echo "<h3>Database Query Result:</h3>";
            if ($result->num_rows === 0) {
                echo "<p class='error'>✗ No user found with email: " . htmlspecialchars($test_email) . "</p>";
                echo "<p>Please check:</p>";
                echo "<ul>";
                echo "<li>Email spelling</li>";
                echo "<li>User was actually registered (check database)</li>";
                echo "</ul>";
            } else {
                $user = $result->fetch_assoc();
                echo "<p class='success'>✓ User found in database</p>";
                echo "<p>User ID: " . $user['id'] . "</p>";
                echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
                echo "<p>Password hash: " . substr($user['password'], 0, 20) . "...</p>";
                
                echo "<h3>Password Verification:</h3>";
                if (password_verify($test_password, $user['password'])) {
                    echo "<p class='success'>✓ Password is CORRECT!</p>";
                    echo "<p>Login should work. If it doesn't, check login.php code.</p>";
                } else {
                    echo "<p class='error'>✗ Password is INCORRECT</p>";
                    echo "<p>Please verify the password you're using.</p>";
                }
            }
            
            $stmt->close();
        }
    }
    ?>
    <br><a href="check_database.php">← Back to Database Check</a>
</body>
</html>
