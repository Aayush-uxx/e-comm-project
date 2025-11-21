<?php
require '../config/db.php';

// Get the category from the query string
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Fetch products based on the category
$result = $conn->query("SELECT * FROM products WHERE category = '$category' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> - MiniGadgets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="products">
    <div class="container">
        <h2><?php echo ucfirst($category); ?></h2>
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" class="product-image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="btn-small">View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products available in this category.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>