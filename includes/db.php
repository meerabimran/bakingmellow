<?php
// Aiven MySQL Connection with SSL (No hardcoded password)
$host = getenv('DB_HOST') ?: 'mysql-c14d984-bakingmellow.l.aivencloud.com';
$port = getenv('DB_PORT') ?: 22551;
$username = getenv('DB_USER') ?: 'avnadmin';
$password = getenv('DB_PASSWORD'); // Password will come from Vercel
$database = getenv('DB_NAME') ?: 'defaultdb';

// SSL Options (Required for Aiven)
$ssl_options = [
    MYSQLI_OPT_SSL_VERIFY_SERVER_CERT => true,
];

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Attempt connection
if (!$conn->real_connect($host, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "✅ Connected to Aiven MySQL successfully!";
?>
