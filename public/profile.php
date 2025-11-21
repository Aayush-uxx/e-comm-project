<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile - MiniGadgets</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      margin: 0;
      background-color: #f4f4f4;
    }

    .profile-container {
      background: #fff;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      width: 400px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
    }

    .profile-info {
      margin-bottom: 20px;
    }

    .btn {
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
    }

    .btn:hover {
      background-color: #45a049;
    }

    .logout-btn {
      background-color: #f44336;
    }

    .logout-btn:hover {
      background-color: #e53935;
    }
  </style>
</head>
<body>

  <div class="profile-container">
    <h2>User Profile</h2>
    <div class="profile-info" id="profile-info">
      <!-- User data will be populated here -->
    </div>
    <button class="btn logout-btn" id="logout-btn">Logout</button>
  </div>

  <script>
    // Fetch user profile data
    async function loadProfile() {
      const response = await fetch('/api/users/profile', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) {
        alert('Unable to load profile.');
        return;
      }

      const result = await response.json();
      if (result && result.profile) {
        document.getElementById('profile-info').innerHTML = `
          <p><strong>Email:</strong> ${result.profile.email}</p>
          <p><strong>Name:</strong> ${result.profile.name || 'Not set'}</p>
        `;
      }
    }

    // Logout the user
    document.getElementById('logout-btn').addEventListener('click', async () => {
      const response = await fetch('/api/users/logout', {
        method: 'POST'
      });

      if (response.ok) {
        alert('Logout successful!');
        window.location.href = 'index.php'; // Redirect to login page
      } else {
        alert('Logout failed.');
      }
    });

    loadProfile(); // Load the profile data on page load
  </script>

</body>
</html>
