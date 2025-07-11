<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "register");

if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

$showPasswordFields = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_email'])) {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;

        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $showPasswordFields = true;
        } else {
            echo "<script>alert('❌ Email not found');</script>";
        }
    } elseif (isset($_POST['reset_password'])) {
        $email = $_SESSION['email'];
        $new_pass = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        if ($new_pass === $confirm_pass) {
            // Store plain password (demo only)
            $query = "UPDATE user SET password='$new_pass' WHERE email='$email'";
            if (mysqli_query($conn, $query)) {
                session_destroy();
                echo "<script>alert('✅ Password reset successfully'); window.location.href = 'Login.php';</script>";
                exit;
            } else {
                echo "<script>alert('❌ Failed to reset password');</script>";
            }
        } else {
            echo "<script>alert('❌ Passwords do not match');</script>";
            $showPasswordFields = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
</head>
<body>
  <!-- Forgot Password Page -->
<div id="forgot-password" class="page">
  <div class="container">
    <form method="POST" class="login__form">
      <h1>🔒 Reset Password</h1>

      <?php if (!$showPasswordFields): ?>
      <!-- Step 1: Enter Email -->
      <div class="input__row">
        <span><i class="ri-mail-line"></i></span>
        <div class="input__group">
          <input type="email" id="reset-email" name="email" placeholder=" " required />
          <label for="reset-email">Email Address</label>
        </div>
      </div>

      <button class="login__btn" type="submit" name="verify_email">Verify Email</button>

      <?php else: ?>
      <!-- Step 2: New & Confirm Password -->
      <div class="input__row">
    <span><i class="ri-lock-2-line"></i></span>
     <div class="input__group">
     <input type="password" class="password-input" name="password" placeholder=" " required>
     <label for="password">New Password</label>
   </div>
    <span class="toggle-eye"><i class="ri-eye-off-line"></i></span>
   </div>

     <div class="input__row">
  <span><i class="ri-lock-2-line"></i></span>
  <div class="input__group">
    <input type="password" class="password-input" name="password" placeholder=" " required>
    <label for="password"> Confirm Password</label>
  </div>
  <span class="toggle-eye"><i class="ri-eye-off-line"></i></span>
</div>


      <button class="login__btn" type="submit" name="reset_password">Reset Password</button>
      <?php endif; ?>

      <div class="register">
        Remembered your password?
        <a href="Login.php" class="back-to-login">Login</a>
      </div>
    </form>
  </div>
</div>
<script src="main.js"></script>
</body>
</html>