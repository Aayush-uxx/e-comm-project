<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../api/auth/admin_login.php');
    exit;
}

require '../config/db.php';

// Get the product ID from the URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    die("Invalid product ID.");
}

// Fetch the product details
$result = $conn->query("SELECT * FROM products WHERE id = $productId");
if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);

    $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $category, $price, $description, $productId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update product.";
    }

    $stmt->close();
    header('Location: manage_products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="edit-product">
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" class="edit-product-form">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="laptops" <?php echo $product['category'] === 'laptops' ? 'selected' : ''; ?>>Laptops</option>
                <option value="smart-watches" <?php echo $product['category'] === 'smart-watches' ? 'selected' : ''; ?>>Smart Watches</option>
                <option value="smart-home-devices" <?php echo $product['category'] === 'smart-home-devices' ? 'selected' : ''; ?>>Smart Home Devices</option>
                <option value="accessories" <?php echo $product['category'] === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
            </select>
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            
            <button type="submit" name="edit_product" class="btn-large">Update Product</button>
        </form>
    </div>
</section>

</body>
</html>