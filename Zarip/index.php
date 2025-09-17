<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Little Thinkers KOTA Puteri</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #fdfdfd;
    }

    .navbar {
      background-color: #2E3A59;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .logo {
      height: 50px;
      margin-right: 15px;
    }

    .brand-name {
      font-size: 1.5rem;
      text-decoration: none;
      color: white;
      font-weight: bold;
    }

    .navbar-nav .nav-link {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-weight: 500;
    }

    .navbar-nav .nav-link:hover {
      text-decoration: underline;
    }

    .hero-section {
      background-size: cover;
      background-position: center;
      padding: 100px 20px;
      color: white;
      text-align: center;
      background-image: url('child care.jpg');
    }

    .hero-text h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .info-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin: 50px 0;
      padding: 20px;
      gap: 20px;
    }

    .info-box {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .info-box h2 {
      color: #2E3A59;
      margin-bottom: 15px;
    }

    .info-box p {
      font-size: 15px;
      color: #333;
    }

    .info-box button {
      margin-top: 15px;
      background-color: #2E3A59;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
    }

    .info-box button:hover {
      background-color: #1d2741;
    }

    .welcome-section {
      text-align: center;
      padding: 80px 20px;
      background-color: #2E3A59;
      color: white;
    }

    .welcome-section hr {
      width: 50%;
      margin: 30px auto;
      border: 1px solid white;
    }

    .header {
      text-align: center;
      background-color: #f0f0f0;
      padding: 40px 20px;
    }

    .step-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 40px 20px;
    }

    .step {
      width: 280px;
      padding: 25px;
      border-radius: 10px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .step-icon img {
      height: 60px;
      margin-bottom: 10px;
    }

    footer {
      background-color: #2E3A59;
      color: white;
      padding: 40px 20px;
    }

    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin-bottom: 20px;
    }

    .footer-column {
      width: 200px;
    }

    .footer-column h3 {
      border-bottom: 1px solid #fff;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }

    .footer-column a {
      display: block;
      color: white;
      text-decoration: none;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .footer-bottom {
      text-align: center;
      border-top: 1px solid #555;
      padding-top: 10px;
    }

    .app-stores img {
      height: 30px;
      margin: 10px;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: white;
      margin: 15% auto;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
    }

    .close {
      float: right;
      font-size: 20px;
      cursor: pointer;
    }

    .login-btn {
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #2E3A59;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="navbar-brand">
    <img src="LTKP logo.jpg" alt="Little Thinkers Kota Puteri" class="logo">
    <a href="#" class="brand-name">Little Thinkers Kota Puteri</a>
  </div>
  <nav class="navbar-nav">
    <a href="index.php" class="nav-link">Home</a>
    <a href="about.php" class="nav-link">About</a>
    <a href="howitworks.php" class="nav-link">How it works</a>
    
    <a href="login.php" class="nav-link">Log in</a>
    <a href="sign_up.php" class="nav-link">Sign up</a>
  </nav>
</header>

<div class="hero-section">
  <div class="hero-text">
    <h1>Babysitter with experience and references</h1>
    <p>Find A Babysitter</p>
  </div>
</div>

<div class="info-section">
  <div class="info-box">
    <h2>Parents, Sign up!</h2>
    <p>Join our community of caring parents! Sign up now to connect with teachers and discover valuable resources for your little ones.</p>
    <a href="sign_up_parents.php"><button>Sign Up</button></a>
  </div>
  <div class="info-box">
    <h2>Become A Babysitter</h2>
    <p>Need a trustworthy babysitter for your child? Explore our network of experienced caregivers ready to help.</p>
    <button onclick="showLoginModal()">Find A Teacher</button>
    <div id="loginModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>You have to login first.</p>
        <a href="login.php"><button class="login-btn">Login</button></a>
      </div>
    </div>
  </div>
  <div class="info-box">
    <h2>About</h2>
    <p>Discover the story behind Little Thinkers Kota Puteri and our mission to create a safe and nurturing environment.</p>
    <a href="about.php"><button>About</button></a>
  </div>
</div>

<section class="welcome-section">
  <h2>Welcome to our website</h2>
  <p>Find all you need to know about hiring a teacher.</p>
  <p>LTKP helps parents locate nannies, after-school carers, and teachers. Access our network of childcare providers today.</p>
  <hr>
</section>

<div class="header">
  <h1>Find A Teacher - Quick & Easy</h1>
</div>
<div class="step-container">
  <div class="step">
    <div class="step-icon"><img src="search.png" alt="Search"></div>
    <h2>Search</h2>
    <ul>
      <li>Check detailed profiles</li>
      <li>Review verifications</li>
      <li>Filter based on your needs</li>
      <li>Sign up required</li>
    </ul>
  </div>
  <div class="step">
    <div class="step-icon"><img src="connect.png" alt="Connect"></div>
    <h2>Connect</h2>
    <ul>
      <li>Secure messaging</li>
      <li>Interview & choose</li>
      <li>Free for teachers</li>
      <li>Affordable for families</li>
    </ul>
  </div>
  <div class="step">
    <div class="step-icon"><img src="meeting.png" alt="Meeting"></div>
    <h2>Introductory Meeting</h2>
    <ul>
      <li>Agree on a time</li>
      <li>Meet in person</li>
    </ul>
  </div>
</div>

<footer>
  <div class="footer">
    <div class="footer-column">
      <h3>Search</h3>
      <a href="#">Find a Teacher</a>
    </div>
    <div class="footer-column">
      <h3>Popular</h3>
      <a href="#">Kuala Lumpur</a>
      <a href="#">Petaling Jaya</a>
      <a href="#">Johor Bahru</a>
      <a href="#">Shah Alam</a>
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
      <a href="https://www.facebook.com/Lthinkers/"><img src="facebook.png" alt="Facebook"></a>
    </div>
  </div>
</footer>

<script>
  function showLoginModal() {
    document.getElementById("loginModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("loginModal").style.display = "none";
  }

  window.onclick = function(event) {
    let modal = document.getElementById("loginModal");
    if (event.target === modal) {
      modal.style.display = "none";
    }
  }
</script>
</body>
</html>
