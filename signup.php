<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


  session_start();

  include("db.php");

  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
      $email = $_POST['email'];
      $password = $_POST['password'];

      if(!empty($email) && !empty($password) && !is_numeric($email))
      {
           $query = "insert into user(email,password) values('$email','$password')";

            mysqli_query($conn, $query);

            echo "<script type ='text/javascript'>alert('Successfully registered ! Now Login');
            window.location.href='Login.php';
            </script>";
            
      }
      else
      {
            echo "<script type ='text/javascript'>alert('Please enter a valid email and password !')</script>";
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel = "stylesheet" href="style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>

  <!--Sign Up-->
<div class="page " id="signup">
        <div class="container">
            <form method="post" action="signup.php" class="login__form">
                <h1>🎉Sign Up</h1>
                <div class="input__row">
                    <span><i class="ri-mail-line"></i></span>
                    <div class="input__group">
                        <input type="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
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

                <button class="login__btn" type="submit">Sign Up</button>
                <div class="register">
                    Already have an account?
                    <a href="Login.php" class="go-to-login">Login</a>
                </div>
            </form>
        </div>
    </div>
<script src="main.js"></script>  
</body>

</html>