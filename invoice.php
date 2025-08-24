<?php
// Protect and connect
require_once 'auth.php';
require_once 'db.php';

// Check for order ID
if (!isset($_GET['order_id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order and customer details using a JOIN
$sql = "SELECT 
            o.id AS order_id, 
            o.order_date, 
            o.total_amount, 
            o.status,
            c.name AS customer_name,
            c.email AS customer_email,
            c.phone AS customer_phone
        FROM orders AS o
        JOIN customers AS c ON o.customer_id = c.id
        WHERE o.id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $order = $result->fetch_assoc();
    } else {
        echo "Invoice not found.";
        exit();
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $order['order_id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        @media print {
            .no-print {
                display: none;
            }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between">
        <h1>Invoice</h1>
        <button class="btn btn-primary no-print" onclick="window.print()">🖨️ Print Invoice</button>
    </div>
    <hr>
    
    <div class="row invoice-header">
        <div class="col-md-6">
            <h4>Bill To:</h4>
            <p>
                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                <?php echo htmlspecialchars($order['customer_email']); ?><br>
                <?php echo htmlspecialchars($order['customer_phone']); ?>
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <h4>Invoice Details:</h4>
            <p>
                <strong>Invoice #:</strong> <?php echo $order['order_id']; ?><br>
                <strong>Date:</strong> <?php echo date("F j, Y", strtotime($order['order_date'])); ?><br>
                <strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?>
            </p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Laundry Services</td>
                <td class="text-end">$<?php echo number_format($order['total_amount'], 2); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="table-light">
                <th class="text-end">Total:</th>
                <th class="text-end">$<?php echo number_format($order['total_amount'], 2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <p>Thank you for your business!</p>
    </div>
</div>

</body>
</html>