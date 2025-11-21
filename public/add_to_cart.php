<?php
session_start();

require '../config/db.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Get the product ID from the POST request
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($productId <= 0) {
    die("Invalid product ID.");
}

// Add product to the cart (stored in the session)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = 1; // Default quantity is 1
} else {
    $_SESSION['cart'][$productId]++; // Increment quantity
}

$_SESSION['success_message'] = "Product added to cart!";
header('Location: cart.php');
exit;
?>