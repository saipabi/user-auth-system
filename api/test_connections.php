<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connection Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 5px 0; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 5px 0; border-radius: 5px; }
        .info { color: blue; background: #d1ecf1; padding: 10px; margin: 5px 0; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Connection Status Check</h1>
    
    <?php
    // Test MySQL
    echo "<h2>1. MySQL Connection</h2>";
    try {
        include "../config/mysql.php";
        if (isset($conn) && !$conn->connect_error) {
            echo "<div class='success'>✓ MySQL Connected Successfully</div>";
            
            // Check database
            $result = $conn->query("SELECT DATABASE()");
            $db = $result->fetch_array()[0];
            echo "<div class='info'>Database: " . $db . "</div>";
            
            // Check users table
            $result = $conn->query("SHOW TABLES LIKE 'users'");
            if ($result && $result->num_rows > 0) {
                echo "<div class='success'>✓ Users table exists</div>";
                
                // Show users
                $result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC LIMIT 5");
                if ($result && $result->num_rows > 0) {
                    echo "<h3>Registered Users:</h3>";
                    echo "<table>";
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
                    echo "<div class='info'>No users registered yet.</div>";
                }
            } else {
                echo "<div class='error'>✗ Users table does NOT exist</div>";
            }
        } else {
            echo "<div class='error'>✗ MySQL Connection Failed</div>";
            if (isset($conn)) {
                echo "<div class='error'>Error: " . $conn->connect_error . "</div>";
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ MySQL Error: " . $e->getMessage() . "</div>";
    }
    
    // Test Redis
    echo "<h2>2. Redis Connection</h2>";
    try {
        include "../config/redis.php";
        if (isset($redis) && $redis !== null) {
            $ping = $redis->ping();
            if ($ping) {
                echo "<div class='success'>✓ Redis Connected Successfully</div>";
            } else {
                echo "<div class='error'>✗ Redis Ping Failed</div>";
            }
        } else {
            echo "<div class='info'>⚠ Redis not available (using fallback token system)</div>";
        }
    } catch (Exception $e) {
        echo "<div class='info'>⚠ Redis not available: " . $e->getMessage() . "</div>";
    }
    
    // Test MongoDB
    echo "<h2>3. MongoDB Connection</h2>";
    try {
        if (file_exists("../vendor/autoload.php")) {
            include "../config/mongo.php";
            if (isset($profileCollection)) {
                $count = $profileCollection->countDocuments([]);
                echo "<div class='success'>✓ MongoDB Connected Successfully</div>";
                echo "<div class='info'>Profiles in database: " . $count . "</div>";
            } else {
                echo "<div class='error'>✗ MongoDB Collection not found</div>";
            }
        } else {
            echo "<div class='info'>⚠ MongoDB vendor/autoload.php not found (run: composer install)</div>";
        }
    } catch (Exception $e) {
        echo "<div class='info'>⚠ MongoDB not available: " . $e->getMessage() . "</div>";
    }
    ?>
    
    <h2>4. Test Login</h2>
    <form method="POST" action="debug_login.php">
        <p>Email: <input type="text" name="email" placeholder="Enter email" required style="padding:5px; width:300px;"></p>
        <p>Password: <input type="password" name="password" placeholder="Enter password" required style="padding:5px; width:300px;"></p>
        <button type="submit" style="padding:10px 20px;">Test Login</button>
    </form>
</body>
</html>
