<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MiniGadgets - Smart Gadgets For You</title>
  <link rel="stylesheet" href="/phpsite/public/style.css">
</head>
<body>
  
<header>
    <div class="navbar">
        <div class="logo">MiniGadgets</div>
        <nav>
            <ul class="nav-links">
                <li><a href="/phpsite/public/index.php">Home</a></li>
                <li><a href="/phpsite/public/product.php">Products</a></li>
                <li><a href="/phpsite/public/about.php">About</a></li>
                <li><a href="/phpsite/public/contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="search-cart-profile">
            <div class="search-box">
                <input type="text" placeholder="Search products...">
                <button class="search-btn">🔍</button>
            </div>
            <!-- Cart Icon -->
            <a href="/phpsite/public/cart.php" class="cart-icon">
    🛒 Cart (<span id="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>)
</a>
            <div class="profile-menu">
                👤
                <div class="dropdown-content">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/phpsite/public/profile/dashboard.php">Your Profile</a>
                        <a href="/phpsite/public/logout.php">Logout</a>
                    <?php else: ?>
                        <a href="/phpsite/public/login.php">Login</a>
                        <a href="/phpsite/public/register.php">Create Account</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
     // Check login status
     const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

// Add event listener to the cart link
document.getElementById('cart-link').addEventListener('click', function (e) {
    if (!isLoggedIn) {
        e.preventDefault(); // Prevent navigation to the cart page
        alert('You must be logged in to view the cart.');
    }
});
    // Optional: Add JavaScript to dynamically update cart count via AJAX
    // Function to update cart count dynamically
function updateCartCount() {
    fetch('/phpsite/public/update_cart_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'get_cart_count' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cart_count;
        }
    })
    .catch(error => console.error('Error updating cart count:', error));
}
</script>

</body>
</html>