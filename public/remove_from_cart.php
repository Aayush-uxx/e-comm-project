<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to remove items from the cart.']);
    exit;
}

$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);

    // If the cart is empty after removing the item, clear the cart
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }

    echo json_encode(['success' => true, 'message' => 'Item removed successfully!', 'cart_count' => isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item.']);
}
exit;