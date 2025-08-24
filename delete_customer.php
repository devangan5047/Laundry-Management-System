<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

// Check if an ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_customers.php");
    exit();
}

$id = $_GET['id'];

// Prepare a delete statement
$sql = "DELETE FROM customers WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_customers.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>