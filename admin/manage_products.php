<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../api/auth/admin_login.php');
    exit;
}

require '../config/db.php';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);

    
// Handle image upload

$image = $_FILES['image'];
$imagePath = '';

if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../images/'; // Ensure this directory exists
    $imageName = basename($image['name']); // Use the original file name
    $imagePath = $uploadDir . $imageName;

    // Check if the file already exists
    if (!file_exists($imagePath)) {
        // Move the uploaded file to the correct directory
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            die("Failed to upload image. Check permissions or directory path.");
        }
    }

    // Use the relative path for the database
    $imagePath = 'images/' . $imageName;
} else {
    die("Image upload failed. Please try again.");
}
    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $category, $price, $description, $imagePath);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        die("Failed to add product: " . $stmt->error);
    }

    $stmt->close();
    header('Location: manage_products.php');
    exit;
}
//if two admin add same product
// $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
// $uniqueName = uniqid('product_', true) . '.' . $ext;
// $imageFullPath = $uploadDir . $uniqueName;

// if (!move_uploaded_file($image['tmp_name'], $imageFullPath)) {
//     die("Failed to move uploaded file.");
// }

// $imagePath = 'images/' . $uniqueName; // Store this in DB

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Panel</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="manage-products">
    <div class="container">
        <h2>Manage Products</h2>

        <!-- Add New Product Button -->
        <a href="#add-product-form" class="btn-large">Add New Product</a>

        <!-- Product List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <img src="/PHPSITE/<?php echo htmlspecialchars($row['image']); ?>" 
     alt="Product Image" 
     class="product-image" 
     style="max-width: 100px;">

                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-small">Edit</a>
                            <form method="POST" action="delete_product.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-small btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Product Form -->
        <form method="POST" id="add-product-form" class="add-product-form" enctype="multipart/form-data">
            <h3>Add New Product</h3>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>
            
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="laptops">Laptops</option>
                <option value="smart-watches">Smart Watches</option>
                
                <option value="accessories">Accessories</option>
                
            </select>
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4"></textarea>
            
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            
            <button type="submit" name="add_product" class="btn-large">Add Product</button>
        </form>
    </div>
</section>

</body>
</html>