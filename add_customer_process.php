<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data and sanitize it
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Simple validation
    if (empty($name) || empty($email) || empty($phone)) {
        // Handle error - for now, just redirect
        header("Location: add_customer.php?error=empty");
        exit();
    }

    // Prepare an insert statement to prevent SQL injection
    $sql = "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $name, $email, $phone);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to customer list with a success message
            header("Location: manage_customers.php?status=success");
            exit();
        } else {
            // Handle error, e.g., duplicate email
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();

} else {
    // If not a POST request, redirect to the form
    header("Location: add_customers.php");
    exit();
}
?>