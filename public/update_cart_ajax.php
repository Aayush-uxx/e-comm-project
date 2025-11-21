<?php
session_start();

header('Content-Type: application/json');

require '../config/db.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add items to the cart.']);
    exit;
}

// Get the product ID from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$productId = isset($data['product_id']) ? intval($data['product_id']) : 0;

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit;
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

// Calculate the total number of items in the cart
$cartCount = array_sum($_SESSION['cart']);

echo json_encode(['success' => true, 'cart_count' => $cartCount]);
exit;
?>