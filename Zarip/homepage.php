<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Little Thinkers Kota Puteri - Find Babysitters</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
  <style>
    /* (Same styles as before, unchanged for brevity) */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f9f9f9;
      color: #333;
      line-height: 1.6;
    }

    header.navbar {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .logo {
      height: 50px;
      margin-right: 10px;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 600;
      color: white;
      text-decoration: none;
    }

    .navbar-nav {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .nav-link {
      color: white;
      text-decoration: none;
      font-weight: 500;
    }

    .nav-link:hover {
      text-decoration: underline;
    }

    .hero-section {
      background-size: cover;
      background-position: center;
      padding: 100px 20px;
      text-align: center;
      color: white;
      background-color: rgba(0, 0, 0, 0.4);
      background-blend-mode: overlay;
    }

    .hero-section h1 {
      font-size: 3rem;
    }

    .hero-section p {
      font-size: 1.5rem;
      margin-top: 10px;
    }

    .info-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 40px 20px;
      background-color: #fff;
    }

    .info-box {
      background-color: #f1f1f1;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .info-box h2 {
      margin-bottom: 10px;
    }

    .info-box button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    .info-box button:hover {
      background-color: #45a049;
    }

    .welcome-section {
      text-align: center;
      padding: 80px 20px;
      background-color: #4CAF50;
      color: white;
      border-radius: 10px;
      margin: 40px 20px;
    }

    .welcome-section h2 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    .welcome-section p {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .step-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 40px 20px;
    }

    .step {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      width: 280px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .step-icon img {
      width: 60px;
      height: 60px;
      margin-bottom: 10px;
    }

    .step h2 {
      margin: 10px 0;
    }

    .step ul {
      text-align: left;
      padding-left: 20px;
    }

    footer {
      background-color: #333;
      color: white;
      padding: 40px 20px 20px;
    }

    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 20px;
    }

    .footer-column {
      flex: 1 1 200px;
    }

    .footer-column h3 {
      margin-bottom: 10px;
    }

    .footer-column a {
      display: block;
      color: #ccc;
      text-decoration: none;
      margin-bottom: 5px;
    }

    .footer-column a:hover {
      color: #fff;
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid #555;
      margin-top: 20px;
    }

    .app-stores img {
      height: 30px;
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      .navbar-nav {
        justify-content: center;
        margin-top: 10px;
      }

      .info-section,
      .step-container,
      .footer {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="navbar-brand">
      <img src="LTKP logo.jpg" alt="Little Thinkers Kota Puteri" class="logo" />
      <a href="#" class="brand-name">Little Thinkers Kota Puteri</a>
    </div>
    <nav class="navbar-nav">
      <a href="homepage.php" class="nav-link">Home</a>
      <a href="find_babysitter.php" class="nav-link">Find Babysitter</a>
      <a href="manage_babysitter.php" class="nav-link">Manage Babysitter</a>
      <a href="view_logs.php" class="nav-link">Log Book</a>
      <a href="Contacts.php" class="nav-link">Contacts</a>
      <a href="parent_profile.php" class="nav-link">👤</a>
    </nav>
  </header>

  <div class="hero-section" style="background-image: url('child care.jpg');">
    <div class="hero-text">
      <h1>Find Experienced Babysitters You Can Trust</h1>
      <p>Connect with caring babysitters for your family’s needs</p>
    </div>
  </div>

  <div class="info-section">
   <div class="info-box">
  <h2>Manage Babysitter</h2>
  <p>Check your babysitter history and manage your payment details securely.</p>
  <a href="manage_babysitter.php"><button>View Payment</button></a>
</div>

    <div class="info-box">
      <h2>Find A Babysitter</h2>
      <p>Search our network of verified babysitters ready to care for your child safely and lovingly.</p>
      <a href="find_babysitter.php"><button>Find A Babysitter</button></a>
    </div>
    <div class="info-box">
      <h2>About Us</h2>
      <p>Learn about our mission to connect parents with reliable and experienced babysitters.</p>
      <a href="about.php"><button>Learn More</button></a>
    </div>
  </div>

  <section class="welcome-section">
    <h2>Welcome to Little Thinkers Kota Puteri</h2>
    <p>Your trusted site for finding and hiring babysitters.</p>
    <p>With our easy-to-use platform, you can review profiles, check references, and connect securely with caregivers suited to your family's unique needs.</p>
    <hr />
  </section>

  <div class="step-container">
    <div class="step">
      <div class="step-icon">
        <img src="search.png" alt="Search Icon" />
      </div>
      <h2>Search</h2>
      <ul>
        <li>Browse detailed babysitter profiles</li>
        <li>View verifications and reviews</li>
        <li>Filter by availability, skills, and location</li>
        <li>Sign up to save favorites and book</li>
      </ul>
    </div>
    <div class="step">
      <div class="step-icon">
        <img src="connect.png" alt="Connect Icon" />
      </div>
      <h2>Connect</h2>
      <ul>
        <li>Use secure messaging to communicate</li>
        <li>Schedule interviews and meetups</li>
        <li>Babysitters join for free</li>
        <li>Affordable fees for parents</li>
      </ul>
    </div>
    <div class="step">
      <div class="step-icon">
        <img src="meeting.png" alt="Meeting Icon" />
      </div>
      <h2>Meet & Hire</h2>
      <ul>
        <li>Arrange introductory meetings</li>
        <li>Build trust and ensure fit</li>
        <li>Confirm your booking securely</li>
      </ul>
    </div>
  </div>

  <footer>
    <div class="footer">
      <div class="footer-column">
        <h3>Search</h3>
        <a href="find_babysitter.php">Find A Babysitter</a>
      </div>
      <div class="footer-column">
        <h3>Popular Areas</h3>
        <a href="#">Babysitters Kuala Lumpur</a>
        <a href="#">Babysitters Petaling Jaya</a>
        <a href="#">Babysitters Johor Bahru</a>
        <a href="#">Babysitters Shah Alam</a>
      </div>
      <div class="footer-column">
        <h3>About</h3>
        <a href="about1.php">About LTKP</a>
        <a href="howitworks1.php">How it works</a>
        <a href="fee1.php">Fee</a>
        <a href="Contacts1.php">Contact</a>
      </div>
      <div class="footer-column">
        <h3>Contact</h3>
        <a href="#">Lorong Geliga Intan 15</a>
        <a href="mailto:khairuddin@gmail.com">khairuddin@gmail.com</a>
        <a href="tel:0166379437">016-6379437</a>
        <a href="tel:0192628025">019-2628025</a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2024 Little Thinkers Kota Puteri
      <div class="app-stores">
        <a href="https://www.facebook.com/Lthinkers/">
          <img src="facebook.png" alt="Facebook" />
        </a>
      </div>
    </div>
  </footer>
</body>
</html>
