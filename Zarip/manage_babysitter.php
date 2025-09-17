<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up - Little Thinkers KOTA Puteri</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(120deg, #f6d365, #fda085);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .signup-selection-container {
      background: white;
      padding: 40px 60px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      text-align: center;
      max-width: 800px;
      width: 100%;
    }

    .signup-selection-container h1 {
      margin-bottom: 40px;
      font-size: 32px;
      color: #333;
    }

    .button-group {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 40px;
      flex-wrap: wrap;
    }

    .option-box {
      flex: 1;
      min-width: 250px;
      background-color: #fff3ea;
      border: 2px solid transparent;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: border-color 0.3s;
    }

    .option-box:hover {
      border-color: #ff914d;
    }

    .role-button {
      padding: 15px 30px;
      font-size: 18px;
      background-color: #ff914d;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s ease;
      cursor: pointer;
      display: inline-block;
      margin-bottom: 15px;
    }

    .role-button:hover {
      background-color: #e9741d;
    }

    .icon {
      width: 80px;
      height: 80px;
      margin-top: 10px;
    }

    .divider {
      width: 2px;
      background-color: #ccc;
      height: 200px;
    }

    .back-button {
      display: inline-block;
      margin-top: 40px;
      background-color: #555;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: #333;
    }

    @media (max-width: 700px) {
      .button-group {
        flex-direction: column;
      }

      .divider {
        display: none;
      }
    }
  </style>
</head>
<body>

  <div class="signup-selection-container">
    <h1>Choose an Option</h1>
    <div class="button-group">
      
      <div class="option-box">
        <a href="manage_payments.php" class="role-button">Manage Payments</a>
        <div>
          <img src="https://img.icons8.com/ios-filled/100/000000/money-bag-euro.png" alt="Payments Icon" class="icon">
        </div>
      </div>

      <div class="divider"></div>

      <div class="option-box">
        <a href="parent_contact_babysitter.php" class="role-button">Contact Babysitter</a>
        <div>
          <img src="https://img.icons8.com/ios-filled/100/000000/chat.png" alt="Contact Icon" class="icon">
        </div>
      </div>

    </div>

    <!-- Back to Home Button -->
    <a href="homepage.php" class="back-button">← Back to Home</a>
  </div>

</body>
</html>
