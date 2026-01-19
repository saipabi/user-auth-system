<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Flow Test</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; }
        .step { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Complete Flow Test</h2>
    
    <div class="step">
        <h3>Step 1: Test Registration</h3>
        <p>Check if you can register a new user</p>
        <a href="../public/signup.html" target="_blank"><button>Go to Signup</button></a>
    </div>
    
    <div class="step">
        <h3>Step 2: Test Database</h3>
        <p>Verify if registration data is saved</p>
        <a href="check_database.php"><button>Check Database</button></a>
    </div>
    
    <div class="step">
        <h3>Step 3: Test Login</h3>
        <p>Try logging in with registered credentials</p>
        <a href="../public/login.html" target="_blank"><button>Go to Login</button></a>
    </div>
    
    <div class="step">
        <h3>Step 4: Test Profile</h3>
        <p>After login, check profile page and save data</p>
        <a href="../public/profile.html" target="_blank"><button>Go to Profile</button></a>
    </div>
    
    <div class="step">
        <h3>Debug Information</h3>
        <?php
        // Check localStorage (client-side only, so just show instructions)
        echo "<p class='info'>Open browser console (F12) and check:</p>";
        echo "<ul>";
        echo "<li>localStorage.getItem('token') - Should show a token after login</li>";
        echo "<li>Check Network tab for API responses</li>";
        echo "<li>Look for any JavaScript errors</li>";
        echo "</ul>";
        ?>
    </div>
    
    <h3>Quick Status Check</h3>
    <?php
    // MySQL Check
    echo "<p><strong>MySQL:</strong> ";
    try {
        include "../config/mysql.php";
        if ($conn && !$conn->connect_error) {
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            $row = $result->fetch_assoc();
            echo "<span class='success'>✓ Connected (" . $row['count'] . " users)</span>";
        } else {
            echo "<span class='error'>✗ Not Connected</span>";
        }
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error: " . $e->getMessage() . "</span>";
    }
    echo "</p>";
    
    // Redis Check
    echo "<p><strong>Redis:</strong> ";
    try {
        include "../config/redis.php";
        if ($redis && $redis->ping()) {
            echo "<span class='success'>✓ Connected</span>";
        } else {
            echo "<span class='error'>✗ Not Connected</span>";
        }
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error: " . $e->getMessage() . "</span>";
    }
    echo "</p>";
    
    // MongoDB Check
    echo "<p><strong>MongoDB:</strong> ";
    try {
        if (file_exists("../vendor/autoload.php")) {
            include "../config/mongo.php";
            if (isset($profileCollection)) {
                $count = $profileCollection->countDocuments([]);
                echo "<span class='success'>✓ Connected (" . $count . " profiles)</span>";
            } else {
                echo "<span class='error'>✗ Collection not found</span>";
            }
        } else {
            echo "<span class='error'>✗ vendor/autoload.php not found (run composer install)</span>";
        }
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error: " . $e->getMessage() . "</span>";
    }
    echo "</p>";
    ?>
</body>
</html>
