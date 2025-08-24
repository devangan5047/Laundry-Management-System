<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];
    $status = $_POST['status'];

    // Simple validation
    if (empty($customer_id) || empty($total_amount) || empty($status)) {
        header("Location: add_order.php?error=empty");
        exit();
    }

    // Prepare an insert statement
    $sql = "INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ids", $customer_id, $total_amount, $status);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to order list with a success message
            header("Location: manage_orders.php?status=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();

} else {
    // If not a POST request, redirect to the form
    header("Location: add_order.php");
    exit();
}
?>