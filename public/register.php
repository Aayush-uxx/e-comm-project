<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - MiniGadgets</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include 'header.php';
?>

<section class="auth-page">
  <div class="container">
    <h2>Register</h2>
    <form id="register-form">
      <label for="name">Full Name</label>
      <input type="text" id="name" placeholder="Full Name" required><br>
      <label for="email">Email</label>
      <input type="email" id="email" placeholder="Email" required><br>
      <label for="password">Password</label>
      <input type="password" id="password" placeholder="Password" required><br>
      <button type="submit" class="btn-large">Register</button>
    </form>
  </div>
</section>

<footer class="footer">
  <p>© 2025 MiniGadgets. All rights reserved.</p>
</footer>

<script>
document.getElementById('register-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  const user = { name, email, password };

  const response = await fetch('/phpsite/api/auth/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(user)
  });

  if (response.ok) {
    alert('Registration successful! Please log in.');
    window.location.href = '/phpsite/public/login.php';
  } else {
    const result = await response.json();
    alert(result.message);
  }
});
</script>

</body>
</html>
