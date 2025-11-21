<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - MiniGadgets</title>
  <link rel="stylesheet" href="/phpsite/public/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="auth-page">
  <div class="container">
    <h2>Admin Login</h2>
    <form id="admin-login-form">
      <label for="email">Email</label>
      <input type="email" id="email" placeholder="Email" required><br>
      <label for="password">Password</label>
      <input type="password" id="password" placeholder="Password" required><br>
      <button type="submit" class="btn-large">Login as Admin</button>
    </form>
  </div>
</section>

<footer class="footer">
  <p>© 2025 MiniGadgets. All rights reserved.</p>
</footer>

<script>
        document.getElementById('admin-login-form').addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent the form from submitting normally

            const email = document.getElementById('email').value; // Get the email input
            const password = document.getElementById('password').value; // Get the password input

            try {
                const response = await fetch('/phpsite/api/auth/admin_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password }) // Send email and password as JSON
                });

                if (response.ok) {
                    alert('Admin login successful!');
                    window.location.href = '/phpsite/admin/dashboard.php'; // Redirect to admin dashboard
                } else {
                    const result = await response.json();
                    alert(result.message); // Show error message if login fails
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>

</body>
</html>