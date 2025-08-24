<?php
// Protect the page
require_once 'auth.php';
// Connect to the database
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Manage Orders</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Orders</h2>
        <a href="add_order.php" class="btn btn-primary">➕ Add New Order</a>
    </div>

    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo '<div class="alert alert-success">Order added successfully!</div>';
    }
    ?>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    // SQL query to fetch orders with customer names
    $sql = "SELECT orders.id, orders.total_amount, orders.status, orders.order_date, customers.name AS customer_name 
            FROM orders 
            JOIN customers ON orders.customer_id = customers.id 
            ORDER BY orders.order_date DESC";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $statuses = ['Pending', 'In Process', 'Completed', 'Delivered'];
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
            echo "<td>$" . htmlspecialchars($row["total_amount"]) . "</td>";
            echo '<td>
                    <select class="form-select status-select" data-order-id="' . $row['id'] . '">';
            foreach ($statuses as $status) {
                $selected = ($row['status'] == $status) ? 'selected' : '';
                echo '<option value="' . $status . '" ' . $selected . '>' . $status . '</option>';
            }
            echo '</select>
                  </td>';
            echo "<td>" . date("d M Y, h:i A", strtotime($row["order_date"])) . "</td>";
            
            // -- THIS IS THE UPDATED ACTIONS COLUMN --
            echo "<td>
                    <a href='invoice.php?order_id=" . $row["id"] . "' class='btn btn-sm btn-info'>View Invoice</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No orders found.</td></tr>";
    }
    $conn->close();
    ?>
</tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Listen for a change on any dropdown with the class 'status-select'
    $('.status-select').change(function() {
        var newStatus = $(this).val();
        var orderId = $(this).data('order-id');

        // Send the data to the server using Ajax
        $.ajax({
            url: 'update_order_status.php', // The PHP file that will process the update
            type: 'POST',
            data: {
                order_id: orderId,
                new_status: newStatus
            },
            success: function(response) {
                // This function is called if the server responds with success
                alert('Status updated successfully!');
                // You could add more sophisticated feedback here
            },
            error: function() {
                // This function is called if the request fails
                alert('Error updating status.');
            }
        });
    });
});
</script>
</body>
</html>