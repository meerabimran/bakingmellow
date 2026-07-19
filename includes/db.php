<?php
$host = 'sql113.infinityfree.com';  // InfinityFree standard host
$username = 'if0_42432631';           // The username you copied in Step 2
$password = 'Meerab11223344'; // The password you set in Step 2
$database = 'if0_42432631_baking_mellow_db'; // The database name you copied in Step 2

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>