<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="order-confirmation">
    <h2>Thank you for your order!</h2>
    <p>Your order has been placed successfully.</p>
    <p>Order ID: <span id="order-id"></span></p>
    <p>Total Amount: $<span id="total-price"></span></p>
    <button onclick="window.location.href='/shop'">Continue Shopping</button>
  </div>

  <script>
    // Assuming you are passing the order ID and total price after successful checkout
    // Here you can fetch order details from backend (e.g., order ID, total price)

    fetch('/api/orders/confirmation')  // Adjust this endpoint according to your backend route
      .then(response => response.json())
      .then(data => {
        document.getElementById('order-id').textContent = data.orderId;
        document.getElementById('total-price').textContent = data.totalPrice;
      });
  </script>

</body>
</html>
