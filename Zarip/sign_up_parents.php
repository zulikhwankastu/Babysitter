<?php
require '_db.php';

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = $_POST['name'];
    $age        = $_POST['age'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $numChildren = intval($_POST['numChildren']);

    // Handle profile image
    $profileImageName = $_FILES['profile_image']['name'];
    $profileImageTmp  = $_FILES['profile_image']['tmp_name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($profileImageName);

    // Create uploads dir if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (!move_uploaded_file($profileImageTmp, $targetFile)) {
        $error = "Failed to upload image.";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert parent
            $stmt = $dbc->prepare("INSERT INTO parents (name, age, email, phone, profile_image, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssss", $name, $age, $email, $phone, $targetFile, $address, $hashedPassword);
            $stmt->execute();

            $parentId = $dbc->insert_id;

            // Insert each child
            for ($i = 1; $i <= $numChildren; $i++) {
                $childName = $_POST["child_name_$i"];
                $childAge  = $_POST["child_age_$i"];

                $stmtChild = $dbc->prepare("INSERT INTO children (parent_id, child_name, child_age) VALUES (?, ?, ?)");
                $stmtChild->bind_param("isi", $parentId, $childName, $childAge);
                $stmtChild->execute();
            }

            // Redirect to login page after successful sign-up
            header("Location: login.php");
            exit;
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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Parent Sign Up - LTKP</title>
  <style>
    body {
      background: linear-gradient(135deg, #f6d365, #fda085);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .form-container {
      background: white;
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 550px;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px 12px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .form-group textarea {
      resize: vertical;
    }

    .child-info {
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #eee;
      background-color: #f9f9f9;
      border-radius: 6px;
    }

    .child-info label {
      font-weight: normal;
    }

    .submit-btn {
      background-color: #ff914d;
      color: white;
      border: none;
      padding: 12px 20px;
      width: 100%;
      font-size: 18px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
      background-color: #e9741d;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #555;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .message.success { color: green; }
    .message.error { color: red; }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Parent Sign Up</h2>

    <?php if ($success): ?>
      <div class="message success">Sign up successful!</div>
    <?php elseif (!empty($error)): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="signupForm" action="" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required />
      </div>

      <div class="form-group">
        <label for="age">Age</label>
        <input type="number" id="age" name="age" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
  <label for="phone">Phone Number</label>
  <input type="text" id="phone" name="phone" required />
</div>

<div class="form-group">
  <label for="profile_image">Profile Image</label>
  <input type="file" id="profile_image" name="profile_image" accept="image/*" required />
</div>


      <div class="form-group">
        <label for="address">Home Address</label>
        <textarea id="address" name="address" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="password">Create Password</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required />
      </div>

      <div class="form-group">
        <label for="numChildren">Number of Children</label>
        <select id="numChildren" name="numChildren" onchange="generateChildFields()" required>
          <option value="">Select</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
      </div>

      <div id="childFields"></div>

      <button type="submit" class="submit-btn">Sign Up</button>
      <a href="sign_up.php" class="back-link">← Back to selection</a>
    </form>
  </div>

  <script>
    function generateChildFields() {
      const container = document.getElementById('childFields');
      container.innerHTML = '';
      const count = document.getElementById('numChildren').value;

      for (let i = 1; i <= count; i++) {
        const childDiv = document.createElement('div');
        childDiv.classList.add('child-info');

        childDiv.innerHTML = `
          <h4>Child ${i}</h4>
          <div class="form-group">
            <label for="child_name_${i}">Name</label>
            <input type="text" id="child_name_${i}" name="child_name_${i}" required />
          </div>
          <div class="form-group">
            <label for="child_age_${i}">Age</label>
            <input type="number" id="child_age_${i}" name="child_age_${i}" required />
          </div>
        `;

        container.appendChild(childDiv);
      }
    }
  </script>

</body>
</html>
