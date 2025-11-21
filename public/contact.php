<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Simulate message sending (replace with actual logic)
    $isMessageSent = true; // Set to false if sending fails

    if ($isMessageSent) {
        $popupMessage = "Message sent successfully!";
    } else {
        $popupMessage = "Failed to send message. Please try again.";
    }

    // Redirect back to the contact page with the popup message
    header("Location: contact.php?popup=" . urlencode($popupMessage));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MiniGadgets - Contact Us</title>
  <link rel="stylesheet" href="/phpsite/public/style.css">
</head>
<body>

<?php
include 'header.php';
?>

<!-- Contact Section -->
<section class="contact-page">
  <div class="container">
    <h2>Contact Us</h2>
    <p>Have a question? Reach out to us!</p>
    <div class="contact-content">
      <div class="contact-info">
        <h3>Get in Touch</h3>
        <p><strong>Phone:</strong> +97798000000</p>
        <p><strong>Email:</strong> support@minigadgets.com</p>
        <p><strong>Address:</strong> Soyambhu, Kathmandu, Nepal</p>
      </div>
      <div class="contact-form">
        <h3>Send Us a Message</h3>
        <form action="contact.php" method="POST">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Your Name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Your Email" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" placeholder="Your Message" required></textarea>
          </div>
          <button type="submit" name="send_message" class="btn">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
include 'footer.php';
?>

<script>
    // Check if a popup message exists in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const popupMessage = urlParams.get('popup');

    if (popupMessage) {
        // Display the popup message
        alert(popupMessage);

        // Remove the popup message from the URL
        history.replaceState(null, null, window.location.pathname);
    }
</script>

</body>
</html>