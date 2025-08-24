<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Validation
    if (empty($id) || empty($name) || empty($email) || empty($phone)) {
        header("Location: manage_customers.php?error=emptyfields");
        exit();
    }

    // Prepare an update statement
    $sql = "UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $name, $email, $phone, $id);

        if ($stmt->execute()) {
            header("Location: manage_customers.php?status=updated");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $stmt->close();
    }
    $conn->close();
} else {
    header("Location: manage_customers.php");
    exit();
}
?>