<?php
include 'dbcon.php';

session_start();

// Checking if cookies are set
if (isset($_COOKIE['username']) && isset($_COOKIE['phone'])) 
{
    // If cookies are set,fill the form
    $savedUsername = $_COOKIE['username'];
    $savedPhoneNum = $_COOKIE['phone'];
} 
else 
{
    $savedUsername = "";
    $savedPhoneNum = "";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="registerstyle.css">
  <link rel="icon" type="image/png" href="Images/denguardlogo.png">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>DenGuard Login</title>
</head>
<body>
  <div class="container">
    <!-- Left Section for Logo -->
    <div class="left-section">
      <img src="Images/DenGuardLogo.png" alt="DenGuard Logo" class="logo">
      <h1 class="brand-name">DENGUARD</h1>
    </div>
    <div class="right-section">
      <h2 class="register-title">LOGIN</h2>
      <form action="loginprocess.php" method="POST">
        
         <input type="text" placeholder="Username" class="input-field" name="username" value="<?php echo htmlspecialchars($savedUsername); ?>"required>

        <input type="text" placeholder="Phone Number" class="input-field" name="phone" value="<?php echo htmlspecialchars($savedPhoneNum); ?>" required>

        <input type="password" placeholder="Password" class="input-field" name="password" required>

        <button type="submit" class="next-button" name="btn-login">LOGIN</button>

        <!-- Divider -->
        <div class="divider">
          <hr class="line"> <span class="or">OR</span> <hr class="line">
        </div>
        
        <p class="terms">New to DenGuard?</p>
        <button type="button" class="register-button" onclick="redirectToRegister()">REGISTER</button><br><br>
        <button type="button" class="register-button" onclick="goBack()">BACK</button>

        <script>
        function redirectToRegister() 
        {
          window.location.href = "register1.php"; 
        }
        function goBack() 
        {
          window.location.href = "home.php"
        }
        </script>
      </form>
    </div>
  </div>

</body>
</html>
