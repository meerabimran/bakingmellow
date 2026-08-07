<?php
// Configure these values in Vercel environment variables. The fallback host is
// only used for local development and must be replaced if the Aiven service changes.
$host = getenv('DB_HOST') ?: 'mysql-c14d984-bakingmellow.l.aivencloud.com';
$port = (int) (getenv('DB_PORT') ?: 22551);
$username = getenv('DB_USER') ?: 'avnadmin';
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME') ?: 'defaultdb';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_init();
    if (!$conn) {
        throw new RuntimeException('Unable to initialize MySQLi.');
    }

    mysqli_ssl_set($conn, null, null, null, null, null);
    $conn->real_connect($host, $username, $password, $database, $port, null, MYSQLI_CLIENT_SSL);
} catch (Throwable $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());
    http_response_code(503);
    exit('The service is temporarily unavailable. Please try again later.');
}
