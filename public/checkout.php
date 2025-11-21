<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - MiniGadgets</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<header>
  <div class="navbar">
    <div class="logo">MiniGadgets</div>
    <nav>
      <ul class="nav-links">
        <li><a href="/">Home</a></li>
        <li><a href="product.php">Products</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
    <div class="search-cart-profile">
      <div class="search-box">
        <input type="text" placeholder="Search products...">
        <button class="search-btn">🔍</button>
      </div>
      <div class="cart-icon">
        🛒 <span id="cart-count">0</span>
      </div>
      <div class="profile-menu">
        👤
        <div class="dropdown-content">
          <a href="login.php" id="login-link">Login</a>
          <a href="register.php" id="register-link">Create Account</a>
          <!-- <a href="#" id="logout-link" style="display: none;">Logout</a>-->
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Checkout Section -->
<section class="checkout-page">
  <div class="container">
    <h2>Checkout</h2>
    <div id="cart-items">
      <!-- Cart items will be dynamically inserted here -->
    </div>
    <div class="checkout-summary">
      <p>Total Items: <span id="total-items">0</span></p>
      <p>Total Price: $<span id="total-price">0</span></p>
    </div>
    <div class="checkout-form">
      <h3>Billing Information</h3>
      <form id="checkout-form">
        <input type="text" id="full-name" placeholder="Full Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="text" id="address" placeholder="Shipping Address" required>
        <input type="text" id="city" placeholder="City" required>
        <input type="text" id="zip-code" placeholder="ZIP Code" required>
        <button type="submit" class="btn-large">Complete Purchase</button>
      </form>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="container grid">
    <div class="footer-about">
      <h3>MiniGadgets</h3>
      <p>Your gateway to smart and portable tech gadgets.</p>
      <p>Got Questions? Call us 24/7 <strong>#877980000000</strong></p>
      <p><strong>support@minigadgets.com</strong></p>
    </div>
    <div class="footer-links">
      <h4>Popular Categories</h4>
      <ul>
        <li><a href="product.php">Laptop & Desktop</a></li>
        <li><a href="product.php">Smart Watches</a></li>
        <li><a href="product.php">Smart Home Devices</a></li>
        <li><a href="product.php">Accessories</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Customer Care</h4>
      <ul>
        <li><a href="#">Your Account</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#">Support Center</a></li>
        <li><a href="/contact">Contact Us</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Company</h4>
      <ul>
        <li><a href="/about">About Us</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms & Conditions</a></li>
        <li><a href="#">FAQs</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 MiniGadgets. All rights reserved.</p>
  </div>
</footer>

<script>
// Authentication and Cart Handling
const checkoutForm = document.getElementById('checkout-form');
const cartItemsSection = document.getElementById('cart-items');
const totalItemsElement = document.getElementById('total-items');
const totalPriceElement = document.getElementById('total-price');

// Fetch Cart Items for Checkout
async function loadCartForCheckout() {
  const response = await fetch('/api/cart');
  const cart = await response.json();
  
  if (response.status === 401) {
    alert('Please log in to proceed with the checkout.');
    return;
  }

  cartItemsSection.innerHTML = '';
  let totalPrice = 0;
  let totalItems = 0;

  cart.items.forEach(item => {
    const cartItem = document.createElement('div');
    cartItem.className = 'cart-item';
    cartItem.innerHTML = `
      <img src="${item.productId.image}" alt="${item.productId.name}" style="width: 50px;">
      <span>${item.productId.name}</span>
      <span>$${item.productId.price}</span>
      <span>Quantity: ${item.quantity}</span>
    `;
    cartItemsSection.appendChild(cartItem);
    totalPrice += item.productId.price * item.quantity;
    totalItems += item.quantity;
  });

  totalItemsElement.textContent = totalItems;
  totalPriceElement.textContent = totalPrice.toFixed(2);
}

// Handle Checkout Form Submission
checkoutForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const fullName = document.getElementById('full-name').value;
  const email = document.getElementById('email').value;
  const address = document.getElementById('address').value;
  const city = document.getElementById('city').value;
  const zipCode = document.getElementById('zip-code').value;

  const orderDetails = {
    fullName,
    email,
    address,
    city,
    zipCode
  };

  // Submit the order
  const response = await fetch('/api/orders/checkout', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(orderDetails)
  });

  if (response.ok) {
    alert('Order placed successfully!');
    // Redirect to a confirmation or home page
    window.location.href = '/';
  } else {
    const result = await response.json();
    alert(result.message);
  }
});

// Load the cart for checkout page
loadCartForCheckout();
</script>

</body>
</html>
