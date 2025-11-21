<?php
session_start();
header('Content-Type: application/json');
require '../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Check if data is received
if (!$data) {
    error_log("No data received in POST request");
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
    exit;
}

if (!isset($data['email'], $data['password'])) {
    error_log("Missing email or password in POST request");
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

// Check if the admin exists
$result = $conn->query("SELECT id, name, email, password FROM admins WHERE email = '$email'");
if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email or password']);
    exit;
}

$admin = $result->fetch_assoc();

// Verify the password
if (!password_verify($password, $admin['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email or password']);
    exit;
}

// Set session variables for admin
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['name'];
$_SESSION['admin_email'] = $admin['email'];
$_SESSION['role'] = 'admin'; // Add role

http_response_code(200);
echo json_encode(['message' => 'Admin login successful']);
$conn->close();
?>