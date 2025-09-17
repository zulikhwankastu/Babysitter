<?php
require '_db.php';

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = $_POST['name'];
    $age          = $_POST['age'];
    $gender       = $_POST['gender'];
    $email        = $_POST['email'];
    $password     = $_POST['password'];
    $confirm      = $_POST['confirm_password'];
    $description  = $_POST['description'];
    $rate         = $_POST['rate'];
    $preferred_start = $_POST['preferred_time_start'];
    $preferred_end   = $_POST['preferred_time_end'];
    $preferred    = $preferred_start . " - " . $preferred_end;
    $address      = $_POST['address'];
    $available    = isset($_POST['available_days']) ? implode(", ", $_POST['available_days']) : "";

    // Handle profile image
    $profileImagePath = "";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
            $profileImagePath = $targetPath;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (empty($error)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $dbc->prepare("INSERT INTO babysitters 
                (name, age, gender, email, password, description, rate, available_days, preferred_time, address, profile_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssssssss", $name, $age, $gender, $email, $hashedPassword, $description, $rate, $available, $preferred, $address, $profileImagePath);
            $stmt->execute();

             header("Location: babysitter_dashboard.php");
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Babysitter Sign Up</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #e0f7fa;
      padding: 30px;
    }

    .form-container {
      background: white;
      padding: 30px;
      max-width: 600px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #00796b;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      font-weight: bold;
      display: block;
    }

    input[type="text"], input[type="email"], input[type="password"], input[type="number"], textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    input[type="file"] {
      margin-top: 5px;
    }

    .checkbox-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .checkbox-group label {
      font-weight: normal;
    }

    .preferred-time {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #00796b;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .success { color: green; }
    .error { color: red; }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Babysitter Sign Up</h2>

  <?php if ($success): ?>
    <div class="message success">Registration successful!</div>
  <?php elseif (!empty($error)): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label>Full Name:</label>
      <input type="text" name="name" required />
    </div>

    <div class="form-group">
      <label>Age:</label>
      <input type="number" name="age" required />
    </div>

    <div class="form-group">
      <label>Gender:</label>
      <select name="gender" required>
        <option value="">--Select--</option>
        <option value="Female">Female</option>
        <option value="Male">Male</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="form-group">
      <label>Email:</label>
      <input type="email" name="email" required />
    </div>

    <div class="form-group">
      <label>Password:</label>
      <input type="password" name="password" required />
    </div>

    <div class="form-group">
      <label>Confirm Password:</label>
      <input type="password" name="confirm_password" required />
    </div>

    <div class="form-group">
      <label>Describe Yourself:</label>
      <textarea name="description" rows="4" required></textarea>
    </div>

    <div class="form-group">
      <label>Rate per Hour (RM):</label>
      <input type="number" name="rate" step="0.01" required />
    </div>

    <div class="form-group">
      <label>Available Days:</label>
      <div class="checkbox-group">
        <?php
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        foreach ($days as $day) {
            echo "<label><input type='checkbox' name='available_days[]' value='$day' /> $day</label>";
        }
        ?>
      </div>
    </div>

    <div class="form-group">
      <label>Preferred Time:</label>
      <div class="preferred-time">
        <input type="time" name="preferred_time_start" required /> to
        <input type="time" name="preferred_time_end" required />
      </div>
    </div>

    <div class="form-group">
      <label>Location:</label>
      <select name="address" required>
        <option value="">--Select Area--</option>
        <option value="Shah Alam">Shah Alam</option>
        <option value="Klang">Klang</option>
        <option value="Kuala Lumpur">Kuala Lumpur</option>
        <option value="Gombak">Gombak</option>
        <option value="Kota Puteri">Kota Puteri</option>
      </select>
    </div>

    <div class="form-group">
      <label>Profile Image:</label>
      <input type="file" name="profile_image" accept="image/*" />
    </div>

    <button type="submit">Sign Up</button>
  </form>
</div>

</body>
</html>
