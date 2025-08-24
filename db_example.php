<?php
// Database connection credentials (TEMPLATE)
// ** Copy this file to db.php and fill in your details **

$servername = "localhost";
$username = "YOUR_DB_USER";
$password = "YOUR_DB_PASSWORD";
$dbname = "laundry_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>