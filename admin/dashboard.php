<?php
session_start();

// Redirect to admin login page if the admin is not logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../api/auth/admin_login.php');
    exit;
}

require '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- <link rel="stylesheet" href="../public/style.css"> -->
</head>
<body>
    <style>
        /* Dashboard Section */
/* Dashboard Styling */
.dashboard {
    min-height: 100vh;
    background: url('/phpsite/images/main.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    max-width: 800px;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.dashboard h2 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 20px;
}

.dashboard p {
    font-size: 1.2rem;
    color: #555;
    margin-bottom: 30px;
}

/* Admin Actions */
.admin-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.btn-large {
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-large:hover {
    background-color: #0056b3;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.dashboard-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.dashboard-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 15px;
}

.dashboard-card p {
    font-size: 1rem;
    color: #777;
    margin-bottom: 20px;
}

.dashboard-card .btn-large {
    display: inline-block;
    padding: 10px 20px;
    font-size: 1rem;
    color: #fff;
    background-color: #3498db;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.dashboard-card .btn-large:hover {
    background-color: #2980b9;
}

/* Profile Section */
.profile-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-photo img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}

.profile-section h3 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.profile-section p {
    font-size: 1rem;
    color: #777;
}

/* Footer */
.footer {
    text-align: center;
    padding: 10px;
    background-color: #2c3e50;
    color: #fff;
    position: absolute;
    bottom: 0;
    width: 100%;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .dashboard h2 {
        font-size: 2rem;
    }

    .dashboard-card h3 {
        font-size: 1.3rem;
    }

    .dashboard-card p {
        font-size: 0.9rem;
    }
}
    </style>

<?php include 'admin_header.php'; ?>

<section class="dashboard">
    <div class="container">
        <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h2>
        <div class="admin-actions">
            <a href="manage_products.php" class="btn-large">Manage Products</a>
            <a href="manage_users.php" class="btn-large">Manage Users</a>
            <a href="register_admin.php" class="btn-large">Register New Admin</a>
            <a href="../public/logout.php" class="btn-large">Logout</a>
        </div>
    </div>
</section>

<footer class="footer">
    <p>© 2025 MiniGadgets. All rights reserved.</p>
</footer>

</body>
</html>