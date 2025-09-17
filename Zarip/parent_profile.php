<?php
session_start();
require '_db.php'; // Make sure this file sets up $conn

// Redirect to login if not logged in
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

$parentId = $_SESSION['parent_id'];

// Fetch parent info
$sql = "SELECT * FROM parents WHERE id = ?";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $parentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Parent not found.";
    exit();
}

$parent = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Parent Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .profile-container {
      max-width: 700px;
      margin: 50px auto;
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .profile-image {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #3f51b5;
      margin-bottom: 20px;
    }

    h2 {
      text-align: center;
      color: #3f51b5;
    }

    .profile-info {
      margin-top: 20px;
    }

    .profile-info label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    .profile-info span {
      display: block;
      margin-top: 5px;
      font-size: 16px;
    }

    .edit-btn {
      display: block;
      text-align: center;
      margin-top: 30px;
    }

    .edit-btn a {
      background-color: #3f51b5;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .edit-btn a:hover {
      background-color: #303f9f;
    }
  </style>
</head>
<body>

<div class="profile-container">
  <div style="text-align: center;">
    <img src="<?php echo !empty($parent['profile_image']) ? htmlspecialchars($parent['profile_image']) : 'default_profile.png'; ?>" alt="Profile Image" class="profile-image">
  </div>
  <h2><?php echo htmlspecialchars($parent['name']); ?>'s Profile</h2>

  <div class="profile-info">
    <label>Email:</label>
    <span><?php echo htmlspecialchars($parent['email']); ?></span>

    <label>Phone:</label>
    <span><?php echo htmlspecialchars($parent['phone']); ?></span>

    <label>Address:</label>
    <span><?php echo htmlspecialchars($parent['address']); ?></span>
  </div>

  <div class="edit-btn">
    <a href="edit_parent_profile.php">Edit Profile</a>
  </div>

    <div class="edit-btn">
    <a href="homepage.php">Back to Homepage</a>
  </div>

    <div class="edit-btn">
    <a href="index.php">Log out</a>
  </div>
</div>

</body>
</html>
