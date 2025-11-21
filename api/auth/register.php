<?php
header('Content-Type: application/json');

// Include the database connection file
require '../../config/db.php';

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['email'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);

// Check if the email is already registered
$result = $conn->query("SELECT id FROM users WHERE email = '$email'");
if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Email is already registered']);
    exit;
}

// Insert the user into the database using prepared statements
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['message' => 'Registration successful']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Registration failed', 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>