<?php
session_start();
include '_db.php';  // Your DB connection file

if (!isset($_SESSION['parent_id'])) {
    echo "You must be logged in as a parent to send a message.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Babysitter ID is required.";
    exit;
}

$babysitter_id = intval($_GET['id']);
$parent_id = intval($_SESSION['parent_id']);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (empty($message)) {
        $error = "Please enter your message.";
    } else {
        $stmt = $dbc->prepare("INSERT INTO contact_messages (parent_id, babysitter_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $parent_id, $babysitter_id, $message);

        if ($stmt->execute()) {
            $success = "Your message has been sent successfully!";
        } else {
            $error = "There was a problem sending your message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Contact Babysitter</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #f2f6fa, #dbeeff);
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            resize: vertical;
            height: 150px;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        a.back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Contact Babysitter</h1>

    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="message">Your Question / Message:</label>
        <textarea name="message" id="message" required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <a class="back-link" href="babysitter_details.php?id=<?php echo $babysitter_id; ?>">← Back to Profile</a>
</div>
</body>
</html>
