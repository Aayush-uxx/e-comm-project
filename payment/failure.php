<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /phpsite/public/login.php');
    exit();
}

// Log the failure
$order_id = isset($_GET['oid']) ? htmlspecialchars($_GET['oid']) : 'Unknown'; // Use 'order_id'
$errorMessage = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : 'Payment failed';

// Update the order status in the database
$stmt = $conn->prepare("UPDATE orders SET status = 'failed' WHERE order_id = ?");
$stmt->bind_param('s', $order_id); // Use 'order_id' to match the database column name
$stmt->execute();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="/phpsite/public/style.css">
</head>
<body>
    <?php include '../header.php'; ?>

    <section class="payment-failure">
        <div class="container">
            <h2>Payment Failed</h2>
            <p>Unfortunately, your payment could not be processed.</p>
            <p><strong>Order ID:</strong> <?= $order_id ?></p> <!-- Use 'order_id' -->
            <p><strong>Error Message:</strong> <?= $errorMessage ?></p>
            <a href="/phpsite/public/cart.php" class="btn-large">Return to Cart</a>
        </div>
    </section>

    <?php include '../footer.php'; ?>
</body>
</html>