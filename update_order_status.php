<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

// Check if the required POST data is received
if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    // Prepare an update statement to prevent SQL injection
    $sql = "UPDATE orders SET status = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $newStatus, $orderId);

        if ($stmt->execute()) {
            // Send a success response back to the Ajax request
            echo json_encode(['status' => 'success', 'message' => 'Status updated.']);
        } else {
            // Send an error response
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
        $stmt->close();
    }
    $conn->close();

} else {
    // If the required data is not provided, send an error
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>