<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Check</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h2>Database Diagnostic Check</h2>
    
    <?php
    echo "<h3>1. MySQL Connection</h3>";
    include "../config/mysql.php";
    
    if (isset($conn) && !$conn->connect_error) {
        echo "<p class='success'>✓ MySQL Connected Successfully</p>";
        echo "<p>Database: " . $conn->query("SELECT DATABASE()")->fetch_array()[0] . "</p>";
        
        // Check if users table exists
        echo "<h3>2. Users Table Check</h3>";
        $result = $conn->query("SHOW TABLES LIKE 'users'");
        if ($result && $result->num_rows > 0) {
            echo "<p class='success'>✓ Users table exists</p>";
            
            // Show table structure
            echo "<h3>3. Table Structure</h3>";
            $result = $conn->query("DESCRIBE users");
            echo "<table>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Show all users
            echo "<h3>4. Registered Users</h3>";
            $result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC");
            if ($result && $result->num_rows > 0) {
                echo "<p class='success'>✓ Found " . $result->num_rows . " registered user(s)</p>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
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
                echo "<p class='warning'>⚠ No users found in database. Try registering first.</p>";
            }
            
            // Test password verification
            echo "<h3>5. Password Verification Test</h3>";
            $testResult = $conn->query("SELECT id, email, password FROM users LIMIT 1");
            if ($testResult && $testResult->num_rows > 0) {
                $user = $testResult->fetch_assoc();
                echo "<p>Testing password hash for user: " . htmlspecialchars($user['email']) . "</p>";
                $hashLength = strlen($user['password']);
                echo "<p>Password hash length: " . $hashLength . " characters</p>";
                echo "<p>Hash format: " . (substr($user['password'], 0, 7) === '$2y$10' ? "BCRYPT (Correct)" : "UNKNOWN FORMAT") . "</p>";
            }
            
        } else {
            echo "<p class='error'>✗ Users table does NOT exist!</p>";
            echo "<p>Please import database/mysql_schema.sql</p>";
        }
    } else {
        echo "<p class='error'>✗ MySQL Connection Failed</p>";
        if (isset($conn)) {
            echo "<p>Error: " . $conn->connect_error . "</p>";
        }
    }
    
    // Check Redis
    echo "<h3>6. Redis Connection</h3>";
    try {
        include "../config/redis.php";
        if (isset($redis)) {
            $redis->ping();
            echo "<p class='success'>✓ Redis Connected Successfully</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Redis Connection Failed</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    // Check MongoDB
    echo "<h3>7. MongoDB Connection</h3>";
    try {
        if (file_exists("../vendor/autoload.php")) {
            include "../config/mongo.php";
            if (isset($profileCollection)) {
                echo "<p class='success'>✓ MongoDB Connected Successfully</p>";
                $profileCount = $profileCollection->countDocuments([]);
                echo "<p>Profiles in MongoDB: " . $profileCount . "</p>";
            }
        } else {
            echo "<p class='warning'>⚠ MongoDB vendor/autoload.php not found. Run 'composer install' first.</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ MongoDB Connection Failed</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <h3>8. Test Login Functionality</h3>
    <form method="POST" action="test_login.php">
        <p>Enter credentials to test login:</p>
        <input type="email" name="test_email" placeholder="Email" required style="padding: 5px; width: 200px;">
        <input type="password" name="test_password" placeholder="Password" required style="padding: 5px; width: 200px;">
        <button type="submit" style="padding: 5px 15px;">Test Login</button>
    </form>
</body>
</html>
