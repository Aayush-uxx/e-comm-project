<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "minigadgets";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Connection successful
?>