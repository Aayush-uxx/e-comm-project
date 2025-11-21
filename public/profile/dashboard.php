<?php
session_start();
include '../../config/db.php'; // Include database connection

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = $conn->prepare('SELECT * FROM users WHERE id = ?');
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Fetch transaction history
$transactions_query = $conn->prepare('SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC');
$transactions_query->bind_param('i', $user_id);
$transactions_query->execute();
$transactions_result = $transactions_query->get_result();
$transactions = $transactions_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="/PHPSITE/public/style.css">
</head>
<body>
    <?php include '../header.php'; ?>

    <section class="dashboard">
        <div class="dashboard-content">
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

            <div class="dashboard-links">
                <a href="/phpsite/public/profile/dashboard.php" class="dashboard-link">Home</a>
                <a href="/phpsite/public/profile/update_settings.php" class="dashboard-link">Settings</a>
                
            </div>

           
        </div>
    </section>

    <?php include '../footer.php'; ?>
</body>
</html>