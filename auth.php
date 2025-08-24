<?php
// Start the session to access session variables
session_start();

// Check if the admin_id session variable is not set
if (!isset($_SESSION['admin_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further script execution
}
?>