<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - MiniGadgets</title>
  <link rel="stylesheet" href="/phpsite/public/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="auth-page">
  <div class="container">
    <h2>Login</h2>
    <form id="login-form">
      <label>Email</label>
      <input type="email" id="email" placeholder="Email" required><br>
      <label>Password</label>
      <input type="password" id="password" placeholder="Password" required><br>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
      <button type="submit" class="btn-large">Login</button>
    </form>
    <div class="admin-login">
      <p>Are you an admin? <a href="/phpsite/public/admin_login.php">Login as Admin</a></p>
    </div>
  </div>
</section>

<footer class="footer">
  <p>© 2025 MiniGadgets. All rights reserved.</p>
</footer>

<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const response = await fetch('/phpsite/api/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });

    if (response.ok) {
        alert('Login successful!');
        window.location.href = '/phpsite/public/profile/dashboard.php';
    } else {
        const result = await response.json();
        alert(result.message);
    }
});
</script>

</body>
</html>