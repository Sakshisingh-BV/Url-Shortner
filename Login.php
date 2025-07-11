<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if ($user_data['password'] == $password) {
                echo "<script>
              localStorage.setItem('isLoggedIn', 'true');
              alert('Login successful!');
              window.location.href = 'home.php';
              </script>";
              exit;
      }
        }
        echo "<script type='text/javascript'>alert('Wrong Username or password!');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Please enter a valid email and password!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

      <link rel="stylesheet" href="style.css" />
     <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
     </head>
    <body>
      
   <div id="login-page" class="page">
  <div class="container">
    <form method="post" action="Login.php" class="login__form">
      <h1>Login</h1>
      <div class="input__row">
        <span><i class="ri-user-3-line"></i></span>
        <div class="input__group">
          <input type="email" name="email" id="email" placeholder=" " required>
          <label for="email">Mail</label>
        </div>
      </div>
      <div class="input__row">
        <span><i class="ri-lock-2-line"></i></span>
        <div class="input__group">
          <input type="password" class="password-input" name="password" placeholder=" " required>
          <label for="password">Password</label>
         </div>
          <span class="toggle-eye"><i class="ri-eye-off-line"></i></span>
      </div>
      <div class="action__btns">
        <div class="remember__me">
          <input type="checkbox" id="check"/>
          <label for="check">Remember me</label>
        </div>
        <a href="forgot.php" class="forgot__password">Forgot Password?</a>
      </div>
      <button class="login__btn" type="submit">Login</button>
      <div class="register">
        Don't have an account?
        <a href="signup.php" class="go-to-signup">Register</a>
      </div>
    </form>
  </div>
</div>
<script src="main.js"></script>
</body>
</html>