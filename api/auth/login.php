<?php
session_start();
header('Content-Type: application/json');
require '../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

// Check if the user exists
$result = $conn->query("SELECT id, name, email, password FROM users WHERE email = '$email'");
if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email or password']);
    exit;
}

$user = $result->fetch_assoc();

// Verify the password
if (!password_verify($password, $user['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email or password']);
    exit;
}

// Set session variables for user
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = 'user'; // Add role

http_response_code(200);
echo json_encode(['message' => 'Login successful']);
$conn->close();
?>