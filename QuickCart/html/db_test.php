<?php
header('Content-Type: text/plain');
// Quick DB connectivity test
$connect_file = __DIR__ . '/../includes/connect.php';
if (!file_exists($connect_file)) {
    echo "connect.php not found at: $connect_file\n";
    exit;
}
include $connect_file;
if (!$con) {
    echo "MySQL connection failed. Check credentials in includes/connect.php and ensure MySQL is running.\n";
    exit;
}
// Try a simple query
$res = mysqli_query($con, "SELECT COUNT(*) AS c FROM customer");
if ($res) {
    $row = mysqli_fetch_assoc($res);
    echo "DB connected. customer count: " . $row['c'] . "\n";
} else {
    echo "DB connected but query failed: " . mysqli_error($con) . "\n";
}
