<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../api/auth/admin_login.php');
    exit;
}

require '../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the email is already registered
    $result = $conn->query("SELECT id FROM admins WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $error = "An admin with this email already exists.";
    } else {
        // Insert the new admin into the database
        $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $success = "Admin account created successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Admin Panel</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="register-admin">
    <div class="container">
        <h2>Register New Admin</h2>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Register Admin</button>
        </form>
    </div>
</section>

<footer class="footer">
    <p>© 2025 MiniGadgets. All rights reserved.</p>
</footer>

</body>
</html>