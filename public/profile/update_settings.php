<?php
session_start();
include '../../config/db.php'; // Include database connection

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = $conn->prepare('SELECT * FROM users WHERE id = ?');
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $new_email = $_POST['new_email'];

    // Validate old password
    if (!password_verify($old_password, $user['password'])) {
        $error = "Old password does not match.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Update email and password in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE users SET email = ?, password = ? WHERE id = ?');
        $stmt->bind_param('ssi', $new_email, $hashed_password, $user_id);
        $stmt->execute();
        $stmt->close();

        $success = "Settings updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Settings</title>
    <!-- <link rel="stylesheet" href="/phpsite/public/style.css"> -->
</head>
<style>
    .settings {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.settings h2 {
    text-align: center;
    margin-bottom: 20px;
}

.settings form {
    display: flex;
    flex-direction: column;
}

.settings label {
    margin-bottom: 5px;
    font-weight: bold;
}

.settings input {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.settings button {
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.settings button:hover {
    background-color: #0056b3;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

.success {
    color: green;
    text-align: center;
    margin-bottom: 10px;
}
</style>
<body>
    <?php include '../header.php'; ?>

    <section class="settings">
        <div class="container">
            <h2>Update Settings</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <label for="old_password">Old Password:</label>
                <input type="password" name="old_password" id="old_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>

                <label for="new_email">New Email:</label>
                <input type="email" name="new_email" id="new_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <button type="submit">Update Settings</button>
            </form>
        </div>
    </section>

    <?php include '../footer.php'; ?>
</body>
</html>