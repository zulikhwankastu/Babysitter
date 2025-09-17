<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About | Little Thinkers Kota Puteri</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #fefefe;
      color: #333;
      line-height: 1.6;
    }

    /* Navbar */
    .navbar {
      background-color: #4a90e2;
      color: #fff;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .navbar-brand .logo {
      width: 50px;
      margin-right: 10px;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 600;
      text-decoration: none;
      color: #fff;
    }

    .navbar-nav {
      display: flex;
      gap: 15px;
    }

    .nav-link {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .nav-link:hover, .nav-link.active {
      text-decoration: underline;
    }

    .login-button, .signup-button {
      padding: 6px 12px;
      border-radius: 5px;
      background-color: #fff;
      color: #4a90e2;
      font-weight: 600;
    }

    .about-container {
      display: flex;
      flex-wrap: wrap;
      padding: 40px 20px;
      align-items: center;
      justify-content: center;
    }

    .about-img img {
      max-width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 400px;
      margin: 20px;
    }

    .about-content {
      max-width: 600px;
      padding: 20px;
    }

    .about-content h1 {
      font-size: 2.2rem;
      color: #4a90e2;
      margin-bottom: 15px;
    }

    .about-content p {
      margin-bottom: 20px;
    }

    .about-list {
      list-style: none;
    }

    .about-list li {
      margin: 10px 0;
      padding-left: 20px;
      position: relative;
    }

    .about-list li::before {
      content: "✔";
      color: #4a90e2;
      font-weight: bold;
      position: absolute;
      left: 0;
    }

    .info-section, .cta-section {
      padding: 40px 20px;
      background-color: #f1f9ff;
      text-align: center;
    }

    .info-section h2, .offerings-title, .cta-section h2 {
      color: #4a90e2;
      font-size: 1.8rem;
      margin-bottom: 15px;
    }

    .offerings {
      padding: 40px 20px;
    }

    .offerings-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 20px;
    }

    .offering-card {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 250px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .offering-card img {
      width: 60px;
      margin-bottom: 10px;
    }

    .slider {
      position: relative;
      max-width: 100%;
      overflow: hidden;
      margin: 30px auto;
    }

    .slides {
      display: flex;
      transition: transform 0.5s ease-in-out;
      width: 600%;
    }

    .slide {
      min-width: 100%;
    }

    .slide img {
  width: 400px;       /* You can adjust this to 300px or whatever size fits best */
  height: auto;
  max-height: 300px;  /* Optional: restrict vertical size */
  margin: 0 auto;
  display: block;
  border-radius: 10px;
  object-fit: cover;
}

    .prev, .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0,0,0,0.5);
      color: white;
      font-size: 2rem;
      padding: 10px;
      cursor: pointer;
      border: none;
      border-radius: 50%;
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    .cta-buttons {
      margin: 20px 0;
    }

    .btn {
      background-color: #4a90e2;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      text-decoration: none;
    }

    .cta-footer {
      color: #777;
      margin-top: 10px;
    }

    footer {
      background-color: #333;
      color: white;
      padding: 40px 20px;
    }

    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      gap: 20px;
    }

    .footer-column h3 {
      margin-bottom: 10px;
    }

    .footer-column a {
      display: block;
      color: white;
      text-decoration: none;
      margin: 5px 0;
      font-size: 0.95rem;
    }

    .footer-column a:hover {
      text-decoration: underline;
    }

    .footer-bottom {
      text-align: center;
      margin-top: 20px;
      border-top: 1px solid #555;
      padding-top: 10px;
    }

    .app-stores img {
      width: 30px;
      margin-left: 10px;
    }

    @media screen and (max-width: 768px) {
      .about-container {
        flex-direction: column;
        text-align: center;
      }

      .offerings-container {
        flex-direction: column;
        align-items: center;
      }

      .navbar-nav {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
  <div class="navbar-brand">
    <img src="LTKP logo.jpg" alt="Logo" class="logo">
    <a href="#" class="brand-name">Little Thinkers Kota Puteri</a>
  </div>
  <nav class="navbar-nav">
    <a href="index.php" class="nav-link">Home</a>
    <a href="about.php" class="nav-link active">About</a>
    <a href="howitworks.php" class="nav-link">How it works</a>
    
    <a href="login.php" class="nav-link login-button">Log in</a>
    <a href="sign_up.php" class="nav-link signup-button">Sign up</a>
  </nav>
</header>

<!-- About Content -->
<main class="about-container">
  <div class="about-img">
    <img src="group photo.jpg" alt="Creative Kids">
  </div>
  <div class="about-content">
    <h1>Welcome to Little Thinkers Kota Puteri (LTKP)</h1>
    <p>We believe every child is unique with limitless potential. Our online system nurtures, educates, and inspires young minds in a safe and engaging environment.</p>
    <ul class="about-list">
      <li>Building Confidence</li>
      <li>Qualified Teachers</li>
      <li>Safe Environment</li>
      <li>Innovative Curriculum</li>
    </ul>
  </div>
</main>

<!-- Mission -->
<section class="info-section">
  <h2>Our Mission</h2>
  <p>We provide top-tier childcare services with personalized learning and compassionate care—accessible, enjoyable, and foundational for lifelong success.</p>
</section>

<!-- Why Choose -->
<section class="offerings">
  <h2 class="offerings-title">Why Choose LTKP?</h2>
  <div class="offerings-container">
    <div class="offering-card">
      <img src="personalized learning.png" alt="Learning">
      <h3>Personalized Learning</h3>
      <p>Adapted to each child’s pace and interests, offering tailored experiences.</p>
    </div>
    <div class="offering-card">
      <img src="expert team.png" alt="Experts">
      <h3>Expert Teachers</h3>
      <p>Experienced educators passionate about nurturing young minds.</p>
    </div>
    <div class="offering-card">
      <img src="safe.png" alt="Safe">
      <h3>Safe & Secure</h3>
      <p>We prioritize security for peace of mind and child safety.</p>
    </div>
    <div class="offering-card">
      <img src="support.png" alt="Support">
      <h3>Community & Support</h3>
      <p>A supportive network for parents and opportunities to connect.</p>
    </div>
  </div>
</section>

<!-- Activity Slider -->
<header class="Activity-header">
  <h1 style="text-align:center;">Activities</h1>
</header>
<div class="slider">
  <div class="slides">
    <div class="slide"><img src="budak mandi.jpg" alt="Activity 1"></div>
    <div class="slide"><img src="jemur baju.jpg" alt="Activity 2"></div>
    <div class="slide"><img src="main pasir.jpg" alt="Activity 3"></div>
    <div class="slide"><img src="main bola.jpg" alt="Activity 4"></div>
    <div class="slide"><img src="melukis.jpg" alt="Activity 5"></div>
    <div class="slide"><img src="main mainan.jpg" alt="Activity 6"></div>
  </div>
  <a class="prev" onclick="plusSlides(-1)">❮</a>
  <a class="next" onclick="plusSlides(1)">❯</a>
</div>

<!-- CTA -->
<section class="cta-section">
  <h2>Join Us in Shaping the Future</h2>
  <p>Be a part of the journey in developing young minds for tomorrow.</p>
  <div class="cta-buttons">
    <a href="register.php" class="btn">Sign Up</a>
  </div>
  <p class="cta-footer">Sign up for free, no commitments</p>
</section>

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
      <a href="#">Babysitter Shah Alam</a>
      <a href="#">Babysitter JB</a>
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
    &copy; 2024 Little Thinkers Kota Puteri
    <div class="app-stores">
      <a href="https://www.facebook.com/Lthinkers/">
        <img src="facebook.png" alt="Facebook">
      </a>
    </div>
  </div>
</footer>

<script>
  let slideIndex = 0;
  showSlides();

  function showSlides() {
    let i;
    const slides = document.querySelectorAll(".slide");
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 4000);
  }

  function plusSlides(n) {
    slideIndex += n - 1;
    showSlides();
  }
</script>

</body>
</html>
