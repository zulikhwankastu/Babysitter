<?php
// Database connection settings (update these!)
require_once '_db.php';



// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for feedback messages
$successMsg = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Simple validation
    if ($name && $email && $subject && $message) {
        // Prepare statement
        $stmt = $dbc->prepare("INSERT INTO feedback (name, email, subject, message, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            if ($stmt->execute()) {
                $successMsg = "Thank you for contacting us! We will get back to you soon.";
                // Clear variables so form resets
                $name = $email = $subject = $message = "";
            } else {
                $errorMsg = "Failed to submit your message. Please try again later.";
            }
            $stmt->close();
        } else {
            $errorMsg = "Database error: Unable to prepare statement.";
        }
    } else {
        $errorMsg = "Please fill in all fields.";
    }
}

$dbc->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Little Thinkers KOTA Puteri</title>
<style>
  /* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
  }
  a {
    text-decoration: none;
    color: #0077cc;
    transition: color 0.3s ease;
  }
  a:hover {
    color: #005999;
  }
  button {
    cursor: pointer;
    background-color: #0077cc;
    border: none;
    color: white;
    padding: 0.7em 1.5em;
    border-radius: 5px;
    font-size: 1rem;
    transition: background-color 0.3s ease;
  }
  button:hover {
    background-color: #005999;
  }

  /* Navbar */
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #005999;
    padding: 1em 2em;
    color: white;
  }
  .navbar-brand {
    display: flex;
    align-items: center;
    gap: 1em;
  }
  .navbar-brand .logo {
    height: 40px;
    border-radius: 6px;
  }
  .brand-name {
    font-weight: 700;
    font-size: 1.3rem;
    color: white;
  }
  .navbar-nav a {
    margin-left: 1.5em;
    font-weight: 600;
    color: white;
  }
  .navbar-nav a:hover {
    text-decoration: underline;
  }

  /* Hero Section */
  .hero-section {
    background: url('contact background.webp') no-repeat center center/cover;
    padding: 4em 2em;
    color: white;
    text-align: center;
    position: relative;
  }
  .hero-section::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 89, 153, 0.6);
    z-index: 0;
  }
  .hero-text {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
  }
  .hero-text h1 {
    font-size: 3rem;
    margin-bottom: 0.5em;
  }
  .hero-text p {
    font-size: 1.25rem;
    font-weight: 500;
  }

  /* FAQ Section */
  .faq-container {
    max-width: 900px;
    margin: 3em auto;
    padding: 0 1em;
  }
  .faq-container h2 {
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 0.3em;
    color: #005999;
  }
  .faq-container p {
    text-align: center;
    margin-bottom: 2em;
    font-size: 1.1rem;
  }
  .faq {
    background: white;
    border-radius: 8px;
    margin-bottom: 1em;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    overflow: hidden;
  }
  .faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1em 1.5em;
    cursor: pointer;
    font-weight: 600;
    background-color: #e6f0ff;
    user-select: none;
  }
  .faq-question:hover {
    background-color: #cce0ff;
  }
  .faq-question span {
    font-size: 1.5rem;
    transition: transform 0.3s ease;
  }
  .faq-answer {
    max-height: 0;
    overflow: hidden;
    padding: 0 1.5em;
    background: white;
    transition: max-height 0.35s ease;
  }
  .faq-answer.open {
    max-height: 200px; /* adjust based on content */
    padding: 1em 1.5em;
  }

  /* Contact Form */
  .contact-form-container {
    background: white;
    max-width: 600px;
    margin: 3em auto 5em;
    padding: 2.5em 2em;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
  }
  .contact-form-container h2 {
    margin-bottom: 0.5em;
    color: #005999;
    text-align: center;
  }
  .contact-form-container p {
    margin-bottom: 1.8em;
    text-align: center;
    font-size: 1.1rem;
    color: #555;
  }
  form label {
    display: block;
    margin-bottom: 0.3em;
    font-weight: 600;
    color: #222;
  }
  form input[type="text"],
  form input[type="email"],
  form textarea {
    width: 100%;
    padding: 0.7em;
    margin-bottom: 1.5em;
    border: 1.8px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s ease;
  }
  form input[type="text"]:focus,
  form input[type="email"]:focus,
  form textarea:focus {
    outline: none;
    border-color: #0077cc;
  }
  form textarea {
    resize: vertical;
    min-height: 100px;
  }
  form button {
    display: block;
    width: 100%;
    font-weight: 700;
  }

  /* Footer */
  footer {
    background-color: #003d66;
    color: white;
    padding: 2em 1em 1em;
    font-size: 0.9rem;
  }
  .footer {
    max-width: 1100px;
    margin: 0 auto 1.5em;
    display: flex;
    flex-wrap: wrap;
    gap: 2em;
    justify-content: space-between;
  }
  .footer-column h3 {
    margin-bottom: 1em;
    font-size: 1.1rem;
    border-bottom: 2px solid #0077cc;
    padding-bottom: 0.3em;
  }
  .footer-column a {
    display: block;
    margin-bottom: 0.5em;
    color: #b3d4ff;
  }
  .footer-column a:hover {
    color: white;
  }
  .footer-bottom {
    border-top: 1px solid #0077cc;
    padding-top: 1em;
    text-align: center;
    font-size: 0.85rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2em;
  }
  .app-stores a img {
    height: 40px;
    filter: brightness(0) invert(1);
    transition: filter 0.3s ease;
  }
  .app-stores a img:hover {
    filter: brightness(0.7) invert(1);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .navbar-nav {
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 0.5em;
    }
    .navbar-nav a {
      margin-left: 1em;
      margin-bottom: 0.3em;
    }
    .footer {
      flex-direction: column;
      gap: 1.5em;
      text-align: center;
    }
    .footer-bottom {
      flex-direction: column;
      gap: 0.5em;
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
    <a href="about1.php" class="nav-link">About</a>
    <a href="howitworks1.php" class="nav-link">How it works</a>
    <a href="fee1.php" class="nav-link">Fee</a>
    <a href="Contacts.php" class="nav-link">Contacts</a>
    <a href="parent_profile.php" class="nav-link" title="Profile">👤</a>
  </nav>
</header>

<section class="hero-section">
  <div class="hero-text">
    <h1>Contact LTKP</h1>
    <p>Browse through our most frequently asked questions or fill in the form below to contact us.</p>
  </div>
</section>

<section class="faq-container" aria-label="Frequently Asked Questions">
  <h2>Do you have a question for us?</h2>
  <p>These are the three most frequently asked questions.</p>

  <div class="faq">
    <div class="faq-question" onclick="toggleFAQ('faq1')" role="button" tabindex="0" aria-expanded="false" aria-controls="faq1">
      <h3>What is LTKP?</h3>
      <span>+</span>
    </div>
    <div class="faq-answer" id="faq1" aria-hidden="true">
      <p>LTKP is a platform that connects parents with babysitters and child care providers.</p>
    </div>
  </div>

  <div class="faq">
    <div class="faq-question" onclick="toggleFAQ('faq2')" role="button" tabindex="0" aria-expanded="false" aria-controls="faq2">
      <h3>Does registration mean any kind of commitment?</h3>
      <span>+</span>
    </div>
    <div class="faq-answer" id="faq2" aria-hidden="true">
      <p>No, registration is free and you can browse profiles without any commitment.</p>
    </div>
  </div>

  <div class="faq">
    <div class="faq-question" onclick="toggleFAQ('faq3')" role="button" tabindex="0" aria-expanded="false" aria-controls="faq3">
      <h3>Are parents and teachers screened by LTKP?</h3>
      <span>+</span>
    </div>
    <div class="faq-answer" id="faq3" aria-hidden="true">
      <p>Yes, we take measures to ensure the safety and reliability of our users.</p>
    </div>
  </div>
</section>

<section class="contact-form-container" aria-label="Contact Form">
  <h2>Contact us</h2>
  <p>Fill out the form below to contact us, or send an email to <a href="mailto:info@ltkp.my">info@ltkp.my</a></p>

  <?php if ($successMsg): ?>
    <p style="color: green; text-align: center; font-weight: bold;"><?php echo htmlspecialchars($successMsg); ?></p>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
    <p style="color: red; text-align: center; font-weight: bold;"><?php echo htmlspecialchars($errorMsg); ?></p>
  <?php endif; ?>

  <form action="" method="post" novalidate>
    <label for="name">Your name</label>
    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name ?? ''); ?>" />

    <label for="email">E-mail address</label>
    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>" />

    <label for="subject">Subject</label>
    <input type="text" id="subject" name="subject" required value="<?php echo htmlspecialchars($subject ?? ''); ?>" />

    <label for="message">Your question or feedback</label>
    <textarea id="message" name="message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>

    <button type="submit">Send message</button>
  </form>
</section>

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
      <a href="#">Help & FAQ</a>
      <a href="#">Contact</a>
      <a href="#">Terms of Service</a>
    </div>
    <div class="footer-column">
      <h3>Read more</h3>
      <a href="#">In the press</a>
      <a href="#">For the press</a>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; 2024 Little Thinkers Kota Puteri
    <div class="app-stores">
      <a href="#" class="app-store" aria-label="App Store">
        <img src="path-to-app-store-image.png" alt="App Store" />
      </a>
      <a href="#" class="app-store" aria-label="Google Play">
        <img src="path-to-google-play-image.png" alt="Google Play" />
      </a>
    </div>
  </div>
</footer>

<script>
  function toggleFAQ(id) {
    const answer = document.getElementById(id);
    const question = answer.previousElementSibling;
    const isOpen = answer.classList.contains('open');
    answer.classList.toggle('open');
    question.setAttribute('aria-expanded', !isOpen);
    answer.setAttribute('aria-hidden', isOpen);
    question.querySelector('span').textContent = isOpen ? '+' : '−';
  }

  document.querySelectorAll('.faq-question').forEach((el) => {
    el.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        el.click();
      }
    });
  });
</script>

</body>
</html>