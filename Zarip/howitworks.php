<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Little Thinkers KOTA Puteri</title>
  <style>
    /* General Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #0b3c5d;
      padding: 15px 30px;
      color: white;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .navbar-brand .logo {
      height: 40px;
      margin-right: 10px;
    }

    .brand-name {
      font-size: 20px;
      font-weight: bold;
      color: white;
    }

    .navbar-nav a {
      margin-left: 20px;
      color: white;
      font-weight: 500;
    }

    .login-button, .signup-button {
      padding: 6px 12px;
      border-radius: 5px;
      background-color: #1d6996;
    }

    .signup-button {
      background-color: #f19c65;
      color: #000;
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(rgba(11, 60, 93, 0.7), rgba(11, 60, 93, 0.7)), url('banner.jpg') center/cover no-repeat;
      color: white;
      padding: 60px 20px;
      text-align: center;
    }

    .hero-text h1 {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .hero-text p {
      font-size: 18px;
    }

    /* Info Section */
    .info-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      padding: 40px 20px;
      background-color: #fff;
    }

    .info-box {
      flex: 1;
      min-width: 280px;
      max-width: 500px;
      margin: 20px;
      background-color: #eef2f5;
      padding: 25px;
      border-radius: 8px;
    }

    .info-box h2 {
      color: #0b3c5d;
    }

    /* How it Works Cards */
    .how-it-works {
      padding: 50px 20px;
      background-color: #fdfdfd;
    }

    .how-it-works .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      margin: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      max-width: 300px;
      flex: 1;
      text-align: center;
    }

    .card h3 {
      color: #0b3c5d;
    }

    /* Steps Section */
    .header {
      text-align: center;
      padding: 40px 20px 10px;
      background-color: #eef4f7;
    }

    .header h1 {
      color: #0b3c5d;
      margin-bottom: 10px;
    }

    .step-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 20px;
      background-color: #eef4f7;
    }

    .step {
      flex: 1;
      min-width: 250px;
      max-width: 300px;
      background-color: #fff;
      margin: 15px;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .step-icon {
      margin-bottom: 15px;
    }

    .step h2 {
      color: #0b3c5d;
    }

    .step ul {
      text-align: left;
      padding-left: 20px;
    }

    /* Footer */
    footer {
      background-color: #0b3c5d;
      color: white;
      padding-top: 30px;
      font-size: 14px;
    }

    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      padding: 20px;
    }

    .footer-column {
      margin: 10px;
      min-width: 150px;
    }

    .footer-column h3 {
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
      margin-bottom: 10px;
      color: #f1f1f1;
    }

    .footer-column a {
      display: block;
      color: #ddd;
      margin-bottom: 6px;
    }

    .footer-bottom {
      text-align: center;
      border-top: 1px solid #ccc;
      padding: 15px;
      background-color: #092c44;
    }

    .app-stores img {
      height: 30px;
      margin-left: 10px;
    }

    @media (max-width: 768px) {
      .navbar-nav {
        display: flex;
        flex-wrap: wrap;
      }
      .step-container, .how-it-works .container, .info-section {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body>

<!-- Navbar -->
<header class="navbar">
  <div class="navbar-brand">
    <img src="LTKP logo.jpg" alt="Little Thinkers Kota Puteri" class="logo">
    <a href="#" class="brand-name">Little Thinkers Kota Puteri</a>
  </div>
  <nav class="navbar-nav">
    <a href="index.php" class="nav-link">Home</a>
    <a href="about.php" class="nav-link">About</a>
    <a href="howitworks.php" class="nav-link">How it works</a>
  
    <a href="login.php" class="nav-link login-button">Log in</a>
    <a href="sign_up.php" class="nav-link signup-button">Sign up</a>
  </nav>
</header>

<!-- Hero Section -->
<header class="hero-section">
  <div class="hero-text">
    <h1>How it works</h1>
    <p>Babysits is a public community platform connecting parents with babysitters</p>
  </div>
</header>

<!-- Info Section -->
<section class="info-section">
  <div class="info-box">
    <h2>Why use LTKP?</h2>
    <p>Whether you are looking for a great babysitter or babysitting job, Babysits makes it easy and transparent. You are in full control of your profile, prices, who you choose to work with, and how you interact with other members.</p>
  </div>
  <div class="info-box">
    <h2>We give you control</h2>
    <p>Read reviews and detailed profiles with trustworthy user verifications. Screen, interview and make your choice.</p>
  </div>
</section>

<!-- How it Works Cards -->
<section class="how-it-works">
  <div class="container">
    <div class="card">
      <img src="easy.webp" alt="Icon 1">
      <h3>You know what's best for you - we just make it easier</h3>
      <p>Whether you are looking for a trustworthy babysitter or babysitting job, Babysits helps make childcare decisions as easy as possible.</p>
    </div>
    <div class="card">
      <img src="safety.webp" alt="Icon 2">
      <h3>We take care of your safety</h3>
      <p>With ID verification, reviews, criminal checks, secure messaging and payments, keeping you and your family safe is our top priority.</p>
    </div>
    <div class="card">
      <img src="peace-of-mind.webp" alt="Icon 3">
      <h3>Less worry - more peace of mind</h3>
      <p>Transparent profiles, helpful tools, and our reliable support team help remove childcare related stress and give you peace of mind.</p>
    </div>
  </div>
</section>

<!-- Step-by-Step Section -->
<div class="header">
  <h1>Find a babysitter or job quick & easy</h1>
</div>
<div class="step-container">
  <div class="step">
    <div class="step-icon">
      <img src="search.png" alt="Search Icon">
    </div>
    <h2>Search</h2>
    <ul>
      <li>Check detailed profiles</li>
      <li>Review trustworthy user verifications</li>
      <li>Filter based on your needs</li>
      <li>Sign up</li>
    </ul>
  </div>
  <div class="step">
    <div class="step-icon">
      <img src="connect.png" alt="Connect Icon">
    </div>
    <h2>Connect</h2>
    <ul>
      <li>Use our secure messaging service</li>
      <li>Screen, interview and choose</li>
      <li>Free for babysitters</li>
      <li>Affordable for families</li>
      <li>Pricing for families and babysitters</li>
    </ul>
  </div>
  <div class="step">
    <div class="step-icon">
      <img src="meeting.png" alt="Meeting Icon">
    </div>
    <h2>Introductory Meeting</h2>
    <ul>
      <li>Agree on a date and time</li>
      <li>Get to know the user in person</li>
    </ul>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="footer">
    <div class="footer-column">
      <h3>Search</h3>
      <a href="#">Find a babysitter</a>
      <a href="#">Find babysitting jobs</a>
    </div>
    <div class="footer-column">
      <h3>Popular</h3>
      <a href="#">Babysitter Kuala Lumpur</a>
      <a href="#">Babysitter Petaling Jaya</a>
      <a href="#">Babysitter Johor Bahru</a>
      <a href="#">Babysitter Shah Alam</a>
    </div>
    <div class="footer-column">
      <h3>About</h3>
      <a href="#">About LTKP</a>
      <a href="#">How it works</a>
      <a href="#">Fee</a>
      <a href="#">Contact</a>
    </div>
    <div class="footer-column">
      <h3>Contact</h3>
      <a href="#">Lorong Geliga Intan 15</a>
      <a href="#">khairuddin@gmail.com</a>
      <a href="#">016-6379437</a>
      <a href="#">019-2628025</a>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; 2024 Little Thinker Kota Puteri
    <div class="app-stores">
      <a href="https://www.facebook.com/Lthinkers/" class="app-store">
        <img src="facebook.png" alt="Facebook">
      </a>
    </div>
  </div>
</footer>

</body>
</html>
