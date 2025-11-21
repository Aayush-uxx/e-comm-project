<?php
session_start();

header('Content-Type: application/json');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to update the cart.']);
    exit;
}

// Ensure the cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the product quantities from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$quantities = isset($data['quantity']) ? $data['quantity'] : [];

if (empty($quantities)) {
    echo json_encode(['success' => false, 'message' => 'No items to update.']);
    exit;
}

require '../config/db.php';

$updatedQuantities = [];
$totalCost = 0;

foreach ($quantities as $id => $quantity) { // Use 'id' instead of 'productId'
    $id = intval($id);
    $quantity = intval($quantity);

    if ($id > 0 && $quantity > 0 && isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $quantity;
        $updatedQuantities[$id] = $quantity;

        // Fetch product price from the database using prepared statements
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param('i', $id); // Use 'id' instead of 'productId'
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $totalCost += $product['price'] * $quantity;
        } else {
            echo json_encode(['success' => false, 'message' => "Product with ID $id not found."]);
            exit;
        }
        $stmt->close();
    }
}
//valiation for quantity
if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity.']);
    exit;
}
echo json_encode(['success' => true, 'message' => 'Cart updated successfully!', 'updated_quantities' => $updatedQuantities, 'total_cost' => $totalCost]);
exit;