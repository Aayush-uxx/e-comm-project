<?php
require '../config/db.php';

// Fetch all products grouped by category
$result = $conn->query("SELECT * FROM products ORDER BY category ASC, created_at DESC");
if (!$result) {
    die("Error fetching products: " . $conn->error);
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products - MiniGadgets</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<section class="products">
    <div class="container">
        <h2>Our Products</h2>
        <?php foreach ($products as $category => $items): ?>
            <div class="category-section">
                <h3><?php echo ucfirst($category); ?></h3>
                <div class="product-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="product-card">
                            <img src="/PHPSITE/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="product-image" />
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="price">Rs.<?php echo number_format($item['price'], 2); ?></p>
                            <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                            <button class="btn-small add-to-cart" data-id="<?php echo $item['id']; ?>">Add to Cart</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<!-- SCRIPT For view more -->
<!-- <script>
    document.querySelectorAll('.view-more').forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            const grid = document.getElementById(`product-grid-${category}`);
            grid.querySelectorAll('.hidden').forEach(card => {
                card.classList.remove('hidden'); // Show hidden items
            });
            this.style.display = 'none'; // Hide the "View More" button
        });
    });
</script> -->
<script>
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            fetch('/phpsite/public/update_cart_ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php include 'footer.php'; ?>

</body>
</html>
