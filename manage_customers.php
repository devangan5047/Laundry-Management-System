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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; // We'll create this reusable navbar next ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Customers</h2>
        <a href="add_customer.php" class="btn btn-primary">➕ Add New Customer</a>
    </div>
    <?php
    // Display success or error messages
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo '<div class="alert alert-success">Customer added successfully!</div>';
        } elseif ($_GET['status'] == 'updated') {
            echo '<div class="alert alert-info">Customer details updated successfully!</div>';
        } elseif ($_GET['status'] == 'deleted') {
            echo '<div class="alert alert-warning">Customer deleted successfully!</div>';
        }
    }
    ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered On</th>
                        <th>Actions</th> </tr>
                    </tr>
                </thead>
                <tbody>
    <?php
    // Fetch customers from the database
    $sql = "SELECT id, name, email, phone, registered_at FROM customers ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
            echo "<td>" . date("d M Y, h:i A", strtotime($row["registered_at"])) . "</td>";
            // -- NEW ACTIONS COLUMN --
            echo "<td>
                    <a href='edit_customer.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='delete_customer.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this customer?\");'>Delete</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No customers found.</td></tr>";
    }
    $conn->close();
    ?>
</tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>