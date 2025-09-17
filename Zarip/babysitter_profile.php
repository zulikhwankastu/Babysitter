<?php
session_start();
require '_db.php';

if (!isset($_SESSION['babysitter_id'])) {
    header("Location: babysitter_login.php");
    exit();
}

$babysitterId = $_SESSION['babysitter_id'];
$error = "";
$success = "";

// Check if editing mode
$isEditMode = isset($_GET['edit']) && $_GET['edit'] == '1';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch form data
    $name     = $_POST['name'];
    $age      = intval($_POST['age']);
    $email    = $_POST['email'];
    $gender   = $_POST['gender'];
    $address  = $_POST['address'];
    $description = $_POST['description'];
    $rate_per_hour = floatval($_POST['rate_per_hour']);
    $preferred_time_start = $_POST['preferred_time_start'];
    $preferred_time_end = $_POST['preferred_time_end'];
    $available_days = isset($_POST['available_days']) ? $_POST['available_days'] : [];
    $available_days_csv = implode(", ", $available_days);

    // Handle profile image upload
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = basename($_FILES['profile_image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            $error = "Only JPG, JPEG, PNG, GIF files are allowed for profile image.";
        } else {
            $newFileName = "babysitter_".$babysitterId."_".time().".".$fileExt;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_image = $destPath;
            } else {
                $error = "Error uploading profile image.";
            }
        }
    }

    if (!$error) {
        if ($profile_image) {
            $sql = "UPDATE babysitters SET name=?, age=?, email=?, gender=?, address=?, description=?, rate=?, preferred_time=?, available_days=?, profile_image=? WHERE id=?";
            $stmt = $dbc->prepare($sql);
            $preferred_time = $preferred_time_start . " - " . $preferred_time_end;
            $stmt->bind_param(
                "sisssdssssi",
                $name, $age, $email, $gender, $address, $description,
                $rate_per_hour, $preferred_time, $available_days_csv,
                $profile_image, $babysitterId
            );
        } else {
            $sql = "UPDATE babysitters SET name=?, age=?, email=?, gender=?, address=?, description=?, rate=?, preferred_time=?, available_days=? WHERE id=?";
            $stmt = $dbc->prepare($sql);
            $preferred_time = $preferred_time_start . " - " . $preferred_time_end;
            $stmt->bind_param(
                "sisssdsssi",
                $name, $age, $email, $gender, $address, $description,
                $rate_per_hour, $preferred_time, $available_days_csv,
                $babysitterId
            );
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            // After update, redirect to avoid resubmission and show view mode
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?success=1");
            exit();
        } else {
            $error = "Failed to update profile: " . $dbc->error;
        }
    }
}

// Fetch babysitter info for display
$stmt = $dbc->prepare("SELECT * FROM babysitters WHERE id = ?");
$stmt->bind_param("i", $babysitterId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Babysitter not found.";
    exit();
}

$babysitter = $result->fetch_assoc();

// Split available days and preferred time
$available_days_arr = explode(", ", $babysitter['available_days'] ?? "");
$days_of_week = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
$preferred_time_start = "";
$preferred_time_end = "";
if (!empty($babysitter['preferred_time'])) {
    $parts = explode(" - ", $babysitter['preferred_time']);
    if (count($parts) === 2) {
        $preferred_time_start = $parts[0];
        $preferred_time_end = $parts[1];
    }
}

// Show success message if redirected after update
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = "Profile updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Babysitter Profile</title>
<style>
  body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
  .container { max-width: 700px; background: white; margin: auto; padding: 30px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
  h1 { color: #3f51b5; text-align: center; margin-bottom: 25px; }
  form label { display: block; margin-top: 15px; font-weight: bold; }
  form input[type="text"], form input[type="number"], form input[type="email"], form textarea, form select, form input[type="time"], form input[type="file"] {
    width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box;
  }
  form textarea { resize: vertical; height: 80px; }
  .days-checkboxes label { display: inline-block; margin-right: 10px; font-weight: normal; }
  .btn-submit, .btn-edit { margin-top: 25px; width: 100%; background: #3f51b5; color: white; padding: 12px; border: none; font-size: 16px; border-radius: 7px; cursor: pointer; }
  .btn-submit:hover, .btn-edit:hover { background: #2c3e9e; }
  .message { margin-top: 20px; font-weight: bold; }
  .success { color: green; }
  .error { color: red; }
  .profile-image {
    display: block;
    margin: 0 auto 15px auto;
    width: 130px;
    height: 130px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #3f51b5;
  }
  .readonly-field {
    padding: 10px 15px;
    background: #f0f0f0;
    border-radius: 5px;
    margin-top: 5px;
    border: 1px solid #ddd;
  }
</style>
</head>
<body>

<div class="container">
  <h1>Your Profile</h1>

  <form action="babysitter_dashboard.php" method="get" style="text-align: center; margin-bottom: 20px;">
  <button type="submit" class="btn-edit" style="width: auto; padding: 8px 20px; font-size: 14px;">Back to Dashboard</button>
</form>

  <?php if ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!$isEditMode): ?>
    <!-- VIEW MODE -->
    <div style="text-align:center;">
      <img src="<?= !empty($babysitter['profile_image']) ? htmlspecialchars($babysitter['profile_image']) : 'default_profile.png'; ?>" alt="Profile Image" class="profile-image" />
    </div>

    <label>Full Name</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['name']) ?></div>

    <label>Age</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['age']) ?></div>

    <label>Email</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['email']) ?></div>

    <label>Gender</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['gender']) ?></div>

    <label>Address</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['address']) ?></div>

    <label>Describe Yourself</label>
    <div class="readonly-field" style="white-space: pre-wrap;"><?= htmlspecialchars($babysitter['description']) ?></div>

    <label>Rate Per Hour (RM)</label>
    <div class="readonly-field"><?= htmlspecialchars(number_format($babysitter['rate'], 2)) ?></div>

    <label>Available Days</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['available_days']) ?></div>

    <label>Preferred Time</label>
    <div class="readonly-field"><?= htmlspecialchars($babysitter['preferred_time']) ?></div>

    <form method="GET" action="">
      <button type="submit" name="edit" value="1" class="btn-edit">Edit Profile</button>
    </form>

  <?php else: ?>
    <!-- EDIT MODE -->
    <form action="" method="POST" enctype="multipart/form-data">
      <div style="text-align:center;">
        <img src="<?= !empty($babysitter['profile_image']) ? htmlspecialchars($babysitter['profile_image']) : 'default_profile.png'; ?>" alt="Profile Image" class="profile-image" />
      </div>

      <label for="profile_image">Change Profile Picture</label>
      <input type="file" name="profile_image" id="profile_image" accept="image/*" />

      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" required value="<?= htmlspecialchars($babysitter['name']) ?>" />

      <label for="age">Age</label>
      <input type="number" name="age" id="age" min="18" max="100" required value="<?= htmlspecialchars($babysitter['age']) ?>" />

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required value="<?= htmlspecialchars($babysitter['email']) ?>" />

      <label for="gender">Gender</label>
      <select name="gender" id="gender" required>
        <option value="">Select</option>
        <option value="Male" <?= ($babysitter['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= ($babysitter['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= ($babysitter['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
      </select>

      <label for="address">Address</label>
      <input type="text" name="address" id="address" required value="<?= htmlspecialchars($babysitter['address']) ?>" />

      <label for="description">Describe Yourself</label>
      <textarea name="description" id="description" required><?= htmlspecialchars($babysitter['description']) ?></textarea>

      <label for="rate_per_hour">Rate Per Hour (RM)</label>
      <input type="number" name="rate_per_hour" id="rate_per_hour" step="0.01" min="0" required value="<?= htmlspecialchars($babysitter['rate']) ?>" />

      <label>Available Days</label>
      <div class="days-checkboxes">
        <?php foreach ($days_of_week as $day): ?>
          <label>
            <input type="checkbox" name="available_days[]" value="<?= $day ?>" <?= in_array($day, $available_days_arr) ? 'checked' : '' ?> />
            <?= $day ?>
          </label>
        <?php endforeach; ?>
      </div>

      <label for="preferred_time_start">Preferred Time Start</label>
      <input type="time" name="preferred_time_start" id="preferred_time_start" required value="<?= htmlspecialchars($preferred_time_start) ?>" />

      <label for="preferred_time_end">Preferred Time End</label>
      <input type="time" name="preferred_time_end" id="preferred_time_end" required value="<?= htmlspecialchars($preferred_time_end) ?>" />

      <button type="submit" class="btn-submit">Save Changes</button>
    </form>

  <?php endif; ?>
</div>

</body>
</html>
