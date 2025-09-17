<?php
require_once '_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        // Hardcoded admin login
        if ($email === 'admin@gmail.com' && $password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            header("Location: admin_dashboard.php");
            exit();
        }

        // Check parents table
        $sql_parent = "SELECT * FROM parents WHERE email = ?";
        if ($stmt_parent = $dbc->prepare($sql_parent)) {
            $stmt_parent->bind_param("s", $email);
            $stmt_parent->execute();
            $result_parent = $stmt_parent->get_result();

            if ($result_parent->num_rows > 0) {
                $parent = $result_parent->fetch_assoc();
                if (password_verify($password, $parent['password'])) {
                    $_SESSION['parent_id'] = $parent['id'];
                    $_SESSION['email'] = $parent['email'];
                    header("Location: homepage.php"); // Change to your parent dashboard page
                    exit();
                } else {
                    $errorMessage = "Invalid password.";
                }
            } else {
                // Check babysitter table
                $sql_babysitter = "SELECT * FROM babysitters WHERE email = ?";
                if ($stmt_babysitter = $dbc->prepare($sql_babysitter)) {
                    $stmt_babysitter->bind_param("s", $email);
                    $stmt_babysitter->execute();
                    $result_babysitter = $stmt_babysitter->get_result();

                    if ($result_babysitter->num_rows > 0) {
                        $babysitter = $result_babysitter->fetch_assoc();
                        if (password_verify($password, $babysitter['password'])) {
                            $_SESSION['babysitter_id'] = $babysitter['id'];
                            $_SESSION['email'] = $babysitter['email'];
                            header("Location: babysitter_dashboard.php"); // Change to your babysitter dashboard page
                            exit();
                        } else {
                            $errorMessage = "Invalid password.";
                        }
                    } else {
                        $errorMessage = "No user found with this email.";
                    }
                    $stmt_babysitter->close();
                } else {
                    $errorMessage = "Error: " . $dbc->error;
                }
            }
            $stmt_parent->close();
        } else {
            $errorMessage = "Error: " . $dbc->error;
        }

    } else {
        $errorMessage = "Both fields are required.";
    }
}

if (isset($dbc)) {
    $dbc->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Form</title>

  <!-- Google Fonts and Material Icons -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone"
    rel="stylesheet"/>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet"/>

  <style>
    /* Reset */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #f6d365, #fda085);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
    }

    .center {
      background: #fff;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 420px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    .title {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 1.8rem;
      text-align: center;
      color: #ff914d;
    }

    .error {
      background-color: #ffdddd;
      border: 1px solid #ff5c5c;
      padding: 10px 15px;
      border-radius: 8px;
      color: #d8000c;
      margin-bottom: 15px;
      text-align: center;
      font-weight: 600;
    }

    .inputs {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .inputf {
      position: relative;
    }

    .inputf input {
      width: 100%;
      padding: 14px 50px 14px 16px;
      font-size: 1rem;
      border: 1.5px solid #ddd;
      border-radius: 10px;
      outline-offset: 2px;
      transition: border-color 0.3s ease;
      font-family: inherit;
    }

    .inputf input:focus {
      border-color: #ff914d;
      box-shadow: 0 0 8px #ff914d33;
    }

    .inputf .label {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      color: #999;
      pointer-events: none;
      transition: 0.2s ease all;
      opacity: 0;
    }

    .inputf input:not(:placeholder-shown) + .label {
      top: 8px;
      font-size: 0.75rem;
      color: #ff914d;
      opacity: 1;
    }

    .inputf .icon {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #ff914d;
      font-size: 1.4rem;
    }

    .text {
      font-size: 0.9rem;
      margin: 15px 0;
      text-align: center;
      color: #555;
    }

    .text a {
      color: #ff914d;
      text-decoration: none;
      font-weight: 600;
    }

    .text a:hover {
      text-decoration: underline;
    }

    .links {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      margin-bottom: 25px;
      font-size: 0.9rem;
      color: #555;
    }

    .links label {
      cursor: pointer;
      user-select: none;
    }

    .links input[type="checkbox"] {
      margin-right: 8px;
      width: 16px;
      height: 16px;
      cursor: pointer;
    }

    .btn {
      background-color: #ff914d;
      border: none;
      color: white;
      font-weight: 700;
      font-size: 1.15rem;
      padding: 14px 0;
      border-radius: 12px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: background-color 0.3s ease;
      user-select: none;
    }

    .btn:hover {
      background-color: #e9741d;
    }

    /* Dot animation on button */
    .dots {
      display: flex;
      justify-content: center;
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      gap: 4px;
    }

    .dot {
      width: 8px;
      height: 8px;
      background: white;
      border-radius: 50%;
      opacity: 0.7;
      animation: blink 1s infinite;
    }

    .dot:nth-child(2) {
      animation-delay: 0.25s;
    }

    .dot:nth-child(3) {
      animation-delay: 0.5s;
    }

    @keyframes blink {
      0%, 100% { opacity: 0.7; }
      50% { opacity: 0.3; }
    }

    .go-back-btn {
      margin-top: 15px;
      background-color: #ddd;
      color: #555;
      font-weight: 600;
      font-size: 1rem;
      padding: 12px 0;
      border-radius: 12px;
      text-align: center;
      display: block;
      text-decoration: none;
      user-select: none;
      transition: background-color 0.3s ease;
    }

    .go-back-btn:hover {
      background-color: #bbb;
      color: #333;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .center {
        padding: 30px 20px;
      }
      .title {
        font-size: 1.6rem;
      }
      .btn {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="center">
    <form action="login.php" method="POST" novalidate>
      <div class="title">Login</div>

      <?php if (isset($errorMessage)): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
      <?php endif; ?>

      <span class="inputs">
        <span class="inputf">
          <input type="email" name="email" class="input" placeholder=" " required />
          <span class="label">Email</span>
          <span class="material-icons icon">email</span>
        </span>
        <span class="inputf">
          <input type="password" name="password" class="input" placeholder=" " required />
          <span class="label">Password</span>
          <span class="material-icons icon">lock</span>
        </span>
      </span>

      <div class="text">
        Forgot password? <a href="forgot_password.php">Click Here to Reset</a>
      </div>

      <div class="links">
        <label for="remember">
          <input type="checkbox" id="remember" />
          Remember Me
        </label>
      </div>

      <button type="submit" class="btn">
        <span>Login</span>
        <div class="dots">
          <div class="dot" style="--delay: 0s"></div>
          <div class="dot" style="--delay: 0.25s"></div>
          <div class="dot" style="--delay: 0.5s"></div>
        </div>
      </button>

      <a href="index.php" class="btn go-back-btn">
        <span>Go Back</span>
      </a>

      <div class="text">
        New user? Create an account <a href="register.php">Register</a>
      </div>
    </form>
  </div>

  <script>
    var btn = document.querySelector(".btn");
    var inputs = document.querySelectorAll(".input");
    btn.onclick = function () {
      btn.classList.toggle("active");
      setTimeout(() => {
        btn.classList.toggle("active");
        inputs[1].classList.toggle("active");
      }, 1500);
      setTimeout(() => {
        inputs[1].classList.toggle("active");
      }, 3000);
    };
  </script>
</body>
</html>

