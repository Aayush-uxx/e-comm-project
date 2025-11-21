<?php
session_start();

// Check if the required query parameters are present
if (isset($_GET['status']) && $_GET['status'] === 'Completed') {
    $transactionId = $_GET['transaction_id'] ?? 'N/A';
    $amount = isset($_GET['amount']) ? intval($_GET['amount']) / 100 : 'N/A'; // Convert paisa to Rs
    $mobile = $_GET['mobile'] ?? 'N/A';
    $purchaseOrderId = $_GET['purchase_order_id'] ?? 'N/A';
    $purchaseOrderName = $_GET['purchase_order_name'] ?? 'N/A';
    $status = $_GET['status'] ?? 'N/A';
} else {
    $status = 'Failed';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-status {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 500px;
        }

        .payment-status h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .payment-status p {
            font-size: 18px;
            color: #34495e;
            margin-bottom: 10px;
        }

        .payment-status .success {
            color: #27ae60;
        }

        .payment-status .failed {
            color: #e74c3c;
        }

        .payment-status button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .payment-status button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="payment-status">
        <?php if ($status === 'Completed'): ?>
            <h1 class="success">Payment Successful!</h1>
            <p>Thank you for your payment.</p>
            <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transactionId) ?></p>
            <p><strong>Amount:</strong> Rs <?= number_format($amount, 2) ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($mobile) ?></p>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($purchaseOrderId) ?></p>
            <p><strong>Order Name:</strong> <?= htmlspecialchars($purchaseOrderName) ?></p>
        <?php else: ?>
            <h1 class="failed">Payment Failed!</h1>
            <p>There was an issue with your payment. Please try again.</p>
        <?php endif; ?>
        <button onclick="window.location.href='/phpsite/public/index.php';">Back to Home</button>
    </div>
</body>
</html>