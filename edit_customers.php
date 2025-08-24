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

// Fetch the customer's data
$sql = "SELECT name, email, phone FROM customers WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
    } else {
        // No customer found with that ID
        header("Location: manage_customers.php?error=notfound");
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Edit Customer</h2>
    <div class="card">
        <div class="card-body">
            <form action="update_customer_process.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Customer</button>
                <a href="manage_customers.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>