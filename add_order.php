<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

// Fetch all customers to populate the dropdown
$customer_sql = "SELECT id, name FROM customers ORDER BY name ASC";
$customers_result = $conn->query($customer_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Add New Order</h2>
    <div class="card">
        <div class="card-body">
            <form action="add_order_process.php" method="POST">
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-select" id="customer_id" name="customer_id" required>
                        <option value="">Select a Customer</option>
                        <?php
                        // Loop through customers and create an option for each
                        if ($customers_result->num_rows > 0) {
                            while($row = $customers_result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total Amount ($)</label>
                    <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Pending" selected>Pending</option>
                        <option value="In Process">In Process</option>
                        <option value="Completed">Completed</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Order</button>
                <a href="manage_orders.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>