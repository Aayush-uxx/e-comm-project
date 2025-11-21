<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../api/auth/admin_login.php');
    exit;
}

require '../config/db.php';

// Get the product ID from the POST request
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($productId <= 0) {
    die("Invalid product ID.");
}

// Delete the product from the database
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Product deleted successfully!";
} else {
    $_SESSION['error_message'] = "Failed to delete product.";
}

$stmt->close();
header('Location: manage_products.php');
exit;
?>