<?php
$host = 'sql302.infinityfree.com';  // InfinityFree standard host
$username = 'if0_XXXXXXX';           // The username you copied in Step 2
$password = 'Your_Database_Password'; // The password you set in Step 2
$database = 'if0_XXXXXXX_baking_mellow_db'; // The database name you copied in Step 2

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>