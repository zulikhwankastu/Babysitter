<?php
require_once '_db.php';

// Fetch babysitters ordered by name
$sql = "SELECT * FROM babysitters ORDER BY name";
$result = $dbc->query($sql);
$babysitters = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $babysitters[] = $row;
    }
}

// Helper to render availability days with class "available"
function renderAvailability($availabilityStr) {
    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    $availableDays = array_map('trim', explode(",", $availabilityStr));
    $html = "";
    foreach ($days as $day) {
        $class = in_array($day, $availableDays) ? "available" : "";
        $shortDay = strtoupper(substr($day, 0, 2));
        $html .= "<span class='$class' title='$day'>$shortDay</span>";
    }
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Babysitter Profiles - Little Thinkers Kota Puteri</title>
<style>
  /* Same styles as before (you can keep your existing styles here) */
  /* ... */

  /* Availability Days */
style>
 /* General Reset & Base */
* {
  box-sizing: border-box;
}
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f0f2f5;
  margin: 0;
  padding: 2rem;
  color: #333;
  line-height: 1.5;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Navbar */
.navbar {
  background-color: #3f51b5;
  padding: 0.75rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  border-radius: 10px;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 700;
  font-size: 1.25rem;
}

.logo {
  height: 40px;
  width: auto;
  object-fit: contain;
}

.brand-name {
  color: white;
  text-decoration: none;
  user-select: none;
}

.navbar-nav {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 600;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  transition: background-color 0.3s ease;
}

.nav-link:hover {
  background-color: #283593;
}

/* Babysitter Profiles Header */
.babysitter-header {
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 2rem;
  color: #3f51b5;
  user-select: none;
}

/* Profiles container layout */
.profiles-container {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

/* Profile Cards */
.profile-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  padding: 1.5rem 1.5rem 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: left;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  max-width: 350px;
  width: 100%;
}

.profile-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

/* Contact Button */
.contact-button {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background-color: #3f51b5;
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
  user-select: none;
}

.contact-button:hover {
  background-color: #283593;
}

/* Profile Image */
.profile-image img {
  width: 130px;
  height: 130px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #3f51b5;
  margin-bottom: 1rem;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

/* Profile Header */
.profile-header {
  text-align: center;
  margin-bottom: 1rem;
  width: 100%;
}

.profile-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #222;
}

.gender-age {
  color: #666;
  font-size: 0.95rem;
  margin-top: 0.25rem;
}

/* Email Link */
.email-link {
  display: block;
  color: #0077cc;
  font-weight: 600;
  margin: 0.5rem 0 1rem;
  text-align: center;
  width: 100%;
  word-break: break-word;
  user-select: text;
}

.email-link:hover {
  text-decoration: underline;
}

/* Rate */
.rate {
  font-weight: 700;
  color: #3f51b5;
  font-size: 1.1rem;
  margin-bottom: 0.8rem;
  text-align: center;
  width: 100%;
}

/* Availability & Preferred Time */
.availability, .preferred-time {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  margin-bottom: 0.75rem;
  color: #555;
  width: 100%;
  flex-wrap: wrap;
  user-select: none;
}

.availability span.available {
  color: #3f51b5;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 4px;
  background-color: #d7dbff;
  cursor: default;
  user-select: none;
}

.availability span {
  font-weight: 700;
}

.preferred-time span {
  font-weight: 700;
  color: #3f51b5;
}

/* Address */
.address {
  font-style: italic;
  color: #444;
  margin-bottom: 1rem;
  text-align: center;
  width: 100%;
  user-select: text;
}

/* About Description */
.about {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 1rem;
  white-space: pre-wrap;
  line-height: 1.4;
  text-align: justify;
  width: 100%;
  user-select: text;
}

/* Created At */
.created-at {
  font-size: 0.8rem;
  color: #999;
  text-align: center;
  width: 100%;
  user-select: none;
}

/* Footer */
footer {
  margin-top: auto;
  background-color: #3f51b5;
  color: white;
  padding: 2rem 1.5rem 1rem;
  border-radius: 10px;
  user-select: none;
}

.footer {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 2rem;
}

.footer-column {
  flex: 1 1 200px;
  min-width: 180px;
}

.footer-column h3 {
  margin-top: 0;
  margin-bottom: 1rem;
  font-weight: 700;
  font-size: 1.1rem;
}

.footer-column a {
  display: block;
  color: #bbdefb;
  margin-bottom: 0.5rem;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.footer-column a:hover {
  color: white;
}

.footer-column p {
  margin: 0.25rem 0;
  font-weight: 400;
  line-height: 1.3;
}

.app-stores a img {
  width: 120px;
  max-width: 100%;
  margin-right: 0.8rem;
  vertical-align: middle;
  user-select: none;
}

.footer-bottom {
  text-align: center;
  margin-top: 1.5rem;
  font-size: 0.9rem;
  color: #c5cae9;
  user-select: none;
}

/* Responsive */
@media (max-width: 900px) {
  .footer {
    flex-direction: column;
    gap: 1.5rem;
  }
}

@media (max-width: 600px) {
  body {
    padding: 1rem;
  }

  .profile-card {
    max-width: 100%;
  }

  .navbar-nav {
    justify-content: center;
    gap: 0.5rem;
  }
}

.view-details-container {
  text-align: center;
  margin-top: 1rem;
}

.view-details-button {
  display: inline-block;
  padding: 0.5rem 1.2rem;
  background-color: #3f51b5;
  color: white;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.view-details-button:hover {
  background-color: #283593;
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
    
    <a href="Contacts.php" class="nav-link">Contact</a>
    <a href="parent_profile.php" class="nav-link">Profile</a>
  </nav>
</header>

<header class="babysitter-header">Babysitter Profiles</header>

<main>
  <section class="profiles-container">
    <?php if (count($babysitters) === 0): ?>
      <p>No babysitters found.</p>
    <?php else: ?>
     <?php foreach ($babysitters as $babysitter): ?>
<article class="profile-card">
  
   
  <div class="profile-image">
    <img src="<?php echo htmlspecialchars($babysitter['profile_image']); ?>" alt="Profile picture of <?php echo htmlspecialchars($babysitter['name']); ?>" />
  </div>
  <div class="profile-header">
    <h2><?php echo htmlspecialchars($babysitter['name']); ?></h2>
    <div class="gender-age"><?php echo htmlspecialchars($babysitter['gender']) . ", " . (int)$babysitter['age'] . " years"; ?></div>
  </div>
  <a class="email-link" href="mailto:<?php echo htmlspecialchars($babysitter['email']); ?>">
    <?php echo htmlspecialchars($babysitter['email']); ?>
  </a>
  <div class="rate">RM <?php echo number_format(floatval($babysitter['rate']), 2); ?> / hour</div>
  <div class="availability">
    Available days: <?php echo renderAvailability($babysitter['available_days']); ?>
  </div>
  <div class="preferred-time">
    Preferred time: <span><?php echo htmlspecialchars($babysitter['preferred_time']); ?></span>
  </div>
  <div class="address">Address: <?php echo htmlspecialchars($babysitter['address']); ?></div>
  <p class="about"><?php echo nl2br(htmlspecialchars($babysitter['description'])); ?></p>
  <div class="created-at">Joined: <?php echo date("M d, Y", strtotime($babysitter['created_at'])); ?></div>
  
  <!-- Added View Details button -->
  <div class="view-details-container">
    <a href="babysitter_details.php?id=<?php echo urlencode($babysitter['id']); ?>" class="view-details-button">View Details</a>
  </div>
</article>
<?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<footer>
  <div class="footer">
    <div class="footer-column">
      <h3>Quick Links</h3>
      <a href="homepage.php">Home</a>
      <a href="about1.php">About Us</a>
      <a href="parent.php">Parent</a>
      <a href="babysitter.php">Babysitter</a>
      <a href="login.php">Login</a>
    </div>
    <div class="footer-column">
      <h3>Contact</h3>
      <p>Little Thinkers Kota Puteri<br />Address, City, Country</p>
      <p>Email: info@littlethinkers.com</p>
      <p>Phone: +60123456789</p>
    </div>
    <div class="footer-column app-stores">
      <h3>Get Our App</h3>
      <a href="#"><img src="appstore.svg" alt="App Store"></a>
      <a href="#"><img src="playstore.svg" alt="Google Play Store"></a>
    </div>
  </div>
  <div class="footer-bottom">&copy; <?php echo date("Y"); ?> Little Thinkers Kota Puteri. All rights reserved.</div>
</footer>

</body>
</html>
