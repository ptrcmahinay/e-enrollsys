<?php 

session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_number = trim($_POST['student_number'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = trim($_POST['password'] ?? '');
    $confirm_pass   = trim($_POST['confirm_password'] ?? '');


    if (!$student_number || !$email || !$password || !$confirm_pass) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password !== $confirm_pass) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ?");
        $stmt->bind_param("s", $student_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if (!$student) {
            $_SESSION['error'] = "Student number not found. Contact registrar.";
        } else {
            $student_id = $student['id'];

            $stmt = $conn->prepare("SELECT users_id FROM users WHERE student_id = ?");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error'] = "Account already exists. Please log in.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (email, password, student_id, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("ssi", $email, $password_hash, $student_id);

                if ($stmt->execute()) {
                    $user_id = $stmt->insert_id;

                    $stmt_role = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, 1)");
                    $stmt_role->bind_param("i", $user_id);
                    $stmt_role->execute();

                    $_SESSION['success'] = "Registration successful! You can now log in.";
                    header("Location: student_register.php");
                    exit;
                } else {
                    $_SESSION['error'] = "Error creating account: " . $conn->error;
                }
            }
        }
    }

    header("Location: student_register.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Register</title>
     <style>
      body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background-color: #0478FF; /* fallback color */
        position: relative; /* needed for ::before */
      }

      body::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url('../assets/img/login_bg.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        opacity: 0.5; /* 50% opacity */
        z-index: 0;
      }

      .register-box {
        width: 100%;
        max-width: 300px;
        padding: 20px;
        background-color: rgba(255,255,255,0.9);
        box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        border-radius: 8px;
        text-align: center;
        position: relative;
        z-index: 1; /* put it above the overlay */
      }

      .logo {
          width: 150px;
          height: auto;
          /* margin-bottom: 20px; */
      }

      h1 {
          color: #0478FF;
          margin-bottom: 5px;
      }

      h2 {
          margin-top: 0;
          margin-bottom: 15px;
          color: #000000ff;
      }

      input[type="text"], input[type="password"], input[type="email"] {
          width: 90%;
          padding: 15px;
          margin: 10px 0;
          border-radius: 10px;
          border: 1px solid #D9D9D9;
          
      }

      button {
          width: 98%;
          padding: 16px;
          background-color: #367AFF;
          color: white;
          border: none;
          border-radius: 10px;
          cursor: pointer;
          font-size: 16px;
      }

      button:hover {
          background-color: #0a3894;
      }

      label {
          display: block;
          text-align: left;
          margin-left: 2%;
      }

      p {
          margin-top: 15px;
          font-size: 16px;
      }

      p a {
          color: #367AFF;
          text-decoration: none;
      }

      .link{
        font-size: 14px;
        color: gray;
      }
    </style>
</head>
<body>
  <div class="register-box">
    <!-- <img src="../assets/img/logo.png" alt="logo" class="logo"> -->
    <h1>Three Academy</h1>
    <h2>Student Registration</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p style='color:green'>{$_SESSION['success']}</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="" method="POST">
        <label>Student Number:</label>
        <input type="text" name="student_number" placeholder="Student Number" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Email" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit">Register</button>

        <p class="link">Already have an account? <a href="login.php">Log in</a></p>
    </form>
</div>
</body>
</html>