<?php
require '../config/db.php';

header('Content-Type: application/json');

$searchQuery = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if (empty($searchQuery)) {
    echo json_encode(['success' => false, 'message' => 'No search query provided.']);
    exit;
}

$result = $conn->query("SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'");

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'image' => $row['image'],
        'description' => $row['description']
    ];
}

echo json_encode(['success' => true, 'products' => $products]);
exit;