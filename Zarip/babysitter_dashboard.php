<?php
session_start();
require '_db.php';

if (!isset($_SESSION['babysitter_id'])) {
    header("Location: babysitter_login.php");
    exit();
}

$babysitterId = $_SESSION['babysitter_id'];

$stmt = $dbc->prepare("SELECT name FROM babysitters WHERE id = ?");
$stmt->bind_param("i", $babysitterId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Babysitter not found.";
    exit();
}

$babysitter = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Babysitter Dashboard</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #eef2f7;
    margin: 0; padding: 0;
  }
  .container {
    max-width: 700px;
    margin: 80px auto;
    background: white;
    padding: 30px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  h1 {
    color: #3f51b5;
    margin-bottom: 40px;
  }

  /* Flex container for buttons */
  .button-group {
    display: flex;
    flex-wrap: wrap; /* wrap on smaller screens */
    justify-content: center; /* center horizontally */
    gap: 20px; /* spacing between buttons */
  }

  /* Button style */
  .btn {
    background-color: #3f51b5;
    color: white;
    padding: 15px 35px;
    font-size: 18px;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
    flex: 1 1 140px; /* flexible basis with min width */
    max-width: 180px;
    text-align: center;
    box-sizing: border-box;
  }

  .btn:hover {
    background-color: #303f9f;
  }

  /* On small screens, stack buttons */
  @media (max-width: 480px) {
    .button-group {
      flex-direction: column;
      gap: 15px;
    }
    .btn {
      max-width: 100%;
      flex: none;
    }
  }
</style>
</head>
<body>
<div class="container">
  <h1>Welcome, <?= htmlspecialchars($babysitter['name']) ?></h1>

  <div class="button-group">
    <a href="babysitter_profile.php" class="btn">Profile</a>
    <a href="babysitter_request.php" class="btn">Requests</a>
    <a href="babysitter_receive_payment.php" class="btn">Payments</a>
     <a href="reply_babysitter.php" class="btn">Answer Question</a>
      <a href="log_activity.php" class="btn">LogBook Activity</a>
    <a href="index.php" class="btn">Log out</a>
  </div>
</div>
</body>
</html>
