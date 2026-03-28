<?php session_start(); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

      .login-box {
        width: 100%;
        max-width: 300px;
        padding: 30px;
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
          margin-bottom: 20px;
          color: #000000ff;
      }

      input[type="text"], input[type="password"] {
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
    </style>
</head>
<body>
    <script>
    function togglePassword() {
        const password = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");

        if (password.type === "password") {
            password.type = "text";
            icon.textContent = "visibility_off";
        } else {
            password.type = "password";
            icon.textContent = "visibility";
        }
    }
    </script>
    <header>
        
    </header>
    <div class="login-box">
        <img src="../assets/img/logo.png" alt="logo" class="logo">
        <h1>E-EnrollSys</h1>
        <h2>Login</h2>
        <form action="login_process.php" method="POST">
            <label>Username / Student Number:</label>
            <input type="text" name="identifier" placeholder="Student Number" required>

            <label>Password:</label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required>

                <span class="material-icons"
                    id="toggleIcon"
                    onclick="togglePassword()"
                    style="position:absolute; right:20px; top:50%; transform:translateY(-50%); cursor:pointer; color:#555;">
                    visibility
                </span>
            </div>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p style='color:red'>{$_SESSION['error']}</p>";
                unset($_SESSION['error']);
            }
            ?>
            <button type="submit">Log in</button>
        </form>

        <p>Don't have an account? <a href="student_register.php">Create your account</a></p>
    </div>
</body>
</html>
