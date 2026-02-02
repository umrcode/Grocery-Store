<?php
include ('../includes/connect.php');

// Drop the old admin table
$drop_query = "DROP TABLE IF EXISTS admin;";
$drop_result = mysqli_query($con, $drop_query);

if ($drop_result) {
    echo "Old admin table dropped.<br>";
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
}

// Create the original admin table with password only
$create_query = "CREATE TABLE IF NOT EXISTS admin (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(50) NOT NULL
);";

$create_result = mysqli_query($con, $create_query);

if ($create_result) {
    echo "Original admin table created successfully.<br>";
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
    exit();
}

// Insert the original admin passwords
$insert_query = "INSERT INTO admin (password) VALUES
('Ali@Hassan123'),
('Faizan@Ali123');";

$insert_result = mysqli_query($con, $insert_query);

if ($insert_result) {
    echo "<strong>✅ Database restored to original state!</strong><br><br>";
    echo "<strong>Admin Passwords:</strong><br>";
    echo "Password 1: Ali@Hassan123<br>";
    echo "Password 2: Faizan@Ali123<br><br>";
    echo "<a href='admin_login.php' class='btn btn-primary'>Go to Admin Login</a>";
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
}

mysqli_close($con);
?>

