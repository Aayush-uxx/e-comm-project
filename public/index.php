<?php 
  include 'header.php'; // Include the header file
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MiniGadgets - Smart Gadgets For You</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Hero Section -->
 
<section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-text">
          <h1>Apple HomePod 2nd Gen Speaker</h1>
          <p>Experience premium sound and smart home features.</p>
          <a href="product.php" class="btn">Shop Now</a>
        </div>
        <div class="hero-image-wrapper">
          <img src="../images/homepod.jpg" alt="Apple HomePod 2nd Gen" class="hero-image">
        </div>
      </div>
    </div>
  </section>
  
  <!-- Category Highlights -->
  <div class="new"><h1>New Arrivals</h1></div>
  <section class="categories">
    <div class="container grid">
      
      <div class="category-card orange">
        <img src="../images/sony.jpg" alt="Samsung Gear Camera">
        <h3>Samsung Gear Camera</h3>
        <a href="product.php" class="btn-small">Shop Now</a>
      </div>
      <div class="category-card green">
        <img src="../images/earpod1.jpg" alt="Beats Studio Buds">
        <h3>Beats Studio Buds</h3>
        <a href="product.php" class="btn-small">Shop Now</a>
      </div>
      <div class="category-card grey">
        <img src="../images/watch4.jpg" alt="apple">
        <h3>Apple Watch Series</h3>
        <a href="product.php" class="btn-small">Shop Now</a>
      </div>
    </div>
  </section>
  <?php
  include 'footer.php';
?>
 





</body>
</html>
