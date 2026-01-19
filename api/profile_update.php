<?php
// Prevent any accidental output from breaking the response
ob_clean();
header('Content-Type: text/plain');

try {
    // Include configurations
    require_once "../config/redis.php";
    require_once "../config/mongo.php";

    // 1. Check MongoDB configuration
    if (!isset($profileCollection) || $profileCollection === null) {
        echo "MONGODB_NOT_CONFIGURED";
        exit;
    }
    
    // 2. Validate Token
    $token = $_POST["token"] ?? '';
    if (empty($token)) {
        echo "TOKEN_REQUIRED";
        exit;
    }
    
    // 3. Get userId from Redis
    $userId = null;
    if (isset($redis) && $redis !== null) {
        // Works with Predis
        $userId = $redis->get($token);
    } 
    
    // Fallback: decode token if Redis is not available
    if (!$userId) {
        $decoded = @base64_decode($token);
        if ($decoded && strpos($decoded, ':') !== false) {
            $parts = explode(':', $decoded);
            $userId = intval($parts[0]);
        }
    }
    
    if (!$userId) {
        echo "INVALID_TOKEN";
        exit;
    }
    
    // 4. Update MongoDB Document
    // Using (int) for age and userId ensures correct data types in MongoDB
    $result = $profileCollection->updateOne(
      ["user_id" => (int)$userId],
      ['$set' => [
        "user_id" => (int)$userId,
        "age" => isset($_POST["age"]) ? (int)$_POST["age"] : 0,
        "dob" => $_POST["dob"] ?? "",
        "contact" => $_POST["contact"] ?? "",
        "updated_at" => date("Y-m-d H:i:s")
      ]],
      ["upsert" => true]
    );
    
    // 5. Return success message (profile.js looks for "Updated")
    if ($result->getModifiedCount() >= 0 || $result->getUpsertedCount() >= 0) {
        echo "Updated";
    } else {
        echo "UPDATE_FAILED";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
exit;