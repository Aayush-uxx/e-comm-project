<?php
session_start();
require '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /phpsite/public/login.php');
    exit();
}
// Generate order_id if not already set
if (!isset($_SESSION['order_id'])) {
    $_SESSION['order_id'] = "ORDER" . time(); // Unique order ID
}
// Fetch cart items
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];
$total = 0;

// Initialize $result to avoid undefined variable error
$result = false;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $conn->prepare("SELECT * FROM products WHERE FIND_IN_SET(id, ?)");
    $stmt->bind_param("s", $ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart[$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $products[] = $row;
        $total += $row['subtotal'];
    }
    $stmt->close();
}

// Error handling 
if (!$result) {
    die("No products found !!! " . $conn->error);
}

// Handle order creation when "Pay" button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    $order_id = "ORDER" . time(); // Unique order ID
    $_SESSION['order_id'] = $order_id;

    $user_id = $_SESSION['user_id'];
    $amount = $total;

    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_id, amount, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("isd", $user_id, $order_id, $amount);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - MiniGadgets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="cart">
    <div class="container">
        <h2>Your Cart</h2>
        <?php if (!empty($products)): ?>
            <form id="update-cart-form">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        <?php foreach ($products as $product): ?>
                            <tr data-id="<?= $product['id'] ?>">
                                <td class="image-container"><img src="/PHPSITE/<?= htmlspecialchars($product['image']) ?>" class="cart-image"></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td class="price">Rs<?= number_format($product['price'], 2) ?></td>
                                <td><input type="number" class="quantity" name="quantity[<?= $product['id'] ?>]" value="<?= $product['quantity'] ?>" min="1"></td>
                                <td class="subtotal">Rs<?= number_format($product['subtotal'], 2) ?></td>
                                <td><button type="button" class="btn-small btn-delete">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h3>Total: Rs<span id="total-price"><?= number_format($total, 2) ?></span></h3>
                <button type="button" id="update-cart-button" class="btn-large">Update Cart</button>
            </form>

            <!-- Pay Button -->
            <div class="payment-section">
                <form method="POST">
                    <input type="hidden" name="create_order" value="1">
                    <button type="submit" id="pay-button" class="btn-large">Pay</button>
                </form>

                <!-- Wallet options will show after Pay is clicked -->
                <?php if (isset($_SESSION['order_id'])): ?>
                    <div id="wallet-options" style="margin-top: 20px;">
                         <?php
                        $order_id = $_SESSION['order_id'];
                        $amount = $total;
                        ?>
                        <form id="khalti-form" action="/phpsite/khalti/payment-request.php" method="POST">
                        <input type="hidden" name="inputAmount4" value="<?= $amount ?>">
                        <input type="hidden" name="inputPurchasedOrderId4" value="<?= $order_id ?>">
                        <input type="hidden" name="inputPurchasedOrderName" value="Cart Payment">
                        <input type="hidden" name="inputName" value="<?= $_SESSION['user_name'] ?? 'Guest' ?>">
                        <input type="hidden" name="inputEmail" value="<?= $_SESSION['user_email'] ?? 'guest@example.com' ?>">
                        <input type="hidden" name="inputPhone" value="<?= $_SESSION['user_phone'] ?? '9800000000' ?>">
                        <input type="hidden" name="submit" value="1"> <!-- Add this field -->
                        <button type="submit" id="khalti-button" class="btn-large khalti-btn" style="background:none; border:none; cursor:pointer;">
                        <img src="../images/khalti.png" alt="Khalti" style="width:230px;">
                        </button>
                        </form>
                       
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</section>

<script>
// Update cart quantities
document.getElementById('update-cart-button').addEventListener('click', function () {
    const rows = document.querySelectorAll('#cart-items tr');
    const quantities = {};

    rows.forEach(row => {
        const productId = row.getAttribute('data-id');
        const quantity = row.querySelector('.quantity').value;
        quantities[productId] = quantity;
    });

    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ quantity: quantities })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let total = 0;
            rows.forEach(row => {
                const id = row.getAttribute('data-id');
                const quantity = data.updated_quantities[id];
                const price = parseFloat(row.querySelector('.price').textContent.replace('Rs', '').trim());
                
                // Ensure quantity and price are valid numbers
                if (!isNaN(quantity) && !isNaN(price)) {
                    const subtotal = price * quantity;
                    row.querySelector('.subtotal').textContent = `Rs${subtotal.toFixed(2)}`;
                    total += subtotal;
                }
            });
            document.getElementById('total-price').textContent = `Rs${total.toFixed(2)}`;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the cart.');
    });
});

// Remove item from cart
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
        const productId = this.closest('tr').getAttribute('data-id');

        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.closest('tr').remove();
                const rows = document.querySelectorAll('#cart-items tr');
                if (rows.length === 0) {
                    document.querySelector('.cart').innerHTML = '<p>Your cart is empty.</p>';
                } else {
                    let total = 0;
                    rows.forEach(row => {
                        const subtotal = parseFloat(row.querySelector('.subtotal').textContent.replace('Rs', '').trim());
                        total += subtotal;
                    });
                    document.getElementById('total-price').textContent = `Rs${total.toFixed(2)}`;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the item.');
        });
    });
});
//redirect to the user to khalti payment page after receving payment_url
document.getElementById('khalti-button').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Send POST request to initiate payment
    fetch('/phpsite/khalti/payment-request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            inputAmount4: <?= $amount ?>, // Pass the amount dynamically
            inputPurchasedOrderId4: '<?= $order_id ?>', // Pass the order ID dynamically
            inputPurchasedOrderName: 'Cart Payment',
            inputName: '<?= $_SESSION['user_name'] ?? 'Guest' ?>', // Pass the user name dynamically
            inputEmail: '<?= $_SESSION['user_email'] ?? 'guest@example.com' ?>', // Pass the user email dynamically
            inputPhone: '<?= $_SESSION['user_phone'] ?? '9800000000' ?>', // Pass the user phone dynamically
            submit: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response from server:', data); // Debugging: Log the response
        if (data.success && data.payment_url) {
            // Redirect to Khalti payment URL
            window.location.href = data.payment_url;
        } else {
            alert(data.message || 'An error occurred while initiating the payment.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while initiating the payment.');
    });
});

</script>

</body>
</html>