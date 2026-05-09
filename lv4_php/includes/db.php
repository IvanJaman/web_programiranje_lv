<?php
// Database connection file
// This file connects to the MySQL database and provides error handling

// Database credentials
$host = "localhost";
$db_user = "root";
$db_pass = "";  // Default XAMPP password is empty
$db_name = "videoteka_db";

// Create connection
$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character encoding to UTF-8 (important for Croatian characters like ž, č, š)
mysqli_set_charset($conn, "utf8mb4");

// Optional: Enable error reporting for development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
