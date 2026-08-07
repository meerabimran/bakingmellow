<?php
// Aiven MySQL connection. Set DB_PASSWORD in Vercel's environment variables.
$host = getenv('DB_HOST') ?: 'mysql-c14d984-bakingmellow.l.aivencloud.com';
$port = (int) (getenv('DB_PORT') ?: 22551);
$username = getenv('DB_USER') ?: 'avnadmin';
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME') ?: 'defaultdb';

$conn = mysqli_init();
mysqli_ssl_set($conn, null, null, null, null, null);

// Handle unavailable DNS/database services without exposing PHP warnings to visitors.
mysqli_report(MYSQLI_REPORT_OFF);
if (!$conn || !$conn->real_connect($host, $username, $password, $database, $port, null, MYSQLI_CLIENT_SSL)) {
    error_log('Database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('The service is temporarily unavailable. Please try again later.');
}
