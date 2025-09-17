<?php
require_once '_db.php';
$babysitter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$error = "";

// Validate babysitter_id
$check = $dbc->prepare("SELECT id, name FROM babysitters WHERE id = ?");
$check->bind_param("i", $babysitter_id);
$check->execute();
$result = $check->get_result();
$babysitter = $result->fetch_assoc();

if (!$babysitter) {
    die("Babysitter not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $content = trim($_POST['content']);
    $rating = intval($_POST['rating']);

    if ($name && $content && $rating >= 1 && $rating <= 5) {
        $stmt = $dbc->prepare("INSERT INTO reviews (babysitter_id, reviewer_name, content, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $babysitter_id, $name, $content, $rating);
        if ($stmt->execute()) {
            header("Location: babysitter_details.php?id=$babysitter_id");
            exit;
        } else {
            $error = "Database error: " . $stmt->error;
        }
    } else {
        $error = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave a Review for <?= htmlspecialchars($babysitter['name']) ?></title>
  <style>
    body { font-family: Arial; background: #f5f5f5; padding: 2rem; }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; }
    input, textarea, select {
      width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      background: #009688; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;
    }
    .error { color: red; margin-bottom: 1rem; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Leave a Review for <?= htmlspecialchars($babysitter['name']) ?></h2>
    <?php if ($error): ?>
      <div class='error'><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label>Your Name</label>
      <input type="text" name="name" required>

      <label>Your Review</label>
      <textarea name="content" rows="5" required></textarea>

      <label>Rating</label>
      <select name="rating" required>
        <option value="">Select rating</option>
        <option value="5">★★★★★ - Excellent</option>
        <option value="4">★★★★☆ - Good</option>
        <option value="3">★★★☆☆ - Average</option>
        <option value="2">★★☆☆☆ - Poor</option>
        <option value="1">★☆☆☆☆ - Bad</option>
      </select>

      <button type="submit">Submit Review</button>
    </form>
  </div>
</body>
</html>
