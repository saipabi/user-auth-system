<?php
// mysql.php
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); // MySQL strictly need this

$success = mysqli_real_connect(
    $conn, 
    "mysql-233abac6-saipabi123-f4e0.j.aivencloud.com", // Host
    "avnadmin", // User
    "AVNS_uwQSL1oBq0Gt_ZS47Zc", // Password
    "defaultdb", // Database Name
    18809 // Port
);

if (!$success) {
    die("Connection failed: " . mysqli_connect_error());
}
?>