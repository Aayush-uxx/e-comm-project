<?php
session_start();
include '../../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Update user data
    $query = $db->prepare('UPDATE users SET name = ?, email = ?, password = IFNULL(?, password) WHERE id = ?');
    $query->execute([$name, $email, $password, $user_id]);

    // Redirect back to dashboard
    header('Location: dashboard.php');
    exit;
}
?>