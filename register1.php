<?php

include 'dbcon.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="registerstyle.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" type="image/png" href="Images/denguardlogo.png">
  <title>DenGuard Register</title>
</head>
<body>
  <div class="container">

    <div class="left-section">
      <img src="Images/DenGuardLogo.png" alt="DenGuard Logo" class="logo">
      <h1 class="brand-name">DENGUARD</h1>
    </div>
    <div class="right-section">
      <h2 class="register-title">REGISTER</h2>
      <form action="register.php" method="POST">

        <input type="text" placeholder="Phone Number" class="input-field" name="phone" required>
        <input type="text" placeholder="Username" class="input-field" name="username" required>
        
        <select name="cmbusertype" id="userType" class="input-field" required>
          <option value="PHI">PHI</option>
          <option value="GN">Grama Niladhari</option>
          <option value="MOH">MOH Officer</option>
          <option value="CP">Community Police</option>
          <option value="GU" selected>General User</option>
        </select>
        
      <select id="division" name="cmbdivision" class="input-field" required>
  <option value="" disabled selected>Select Division</option>
  <?php
  $query = "SELECT Division_ID, Division_Name FROM division";
  $result = mysqli_query($conn, $query);
  echo $query;
  if (!$result) 
  {
      die("Query failed: " . mysqli_error($conn));
  } 
  else 
  {
      while ($row = mysqli_fetch_array($result)) 
      {
          echo "<option value=$row[0]>$row[1]</option>";
      }
  }
  ?>
</select>


        <div id="dynamicFields"></div>
        
        <input type="text" placeholder="Address" class="input-field" name="address" required>
        <input type="password" placeholder="Password" class="input-field" name="password" required>

        <button type="submit" class="next-button" name="btn-register">REGISTER</button>

        <div class="divider">
          <hr class="line"> <span class="or">OR</span> <hr class="line">
        </div>
        
        <button type="button" class="register-button" onclick="redirectToLogin()">LOGIN</button>
      </form>
      
      <p class="terms">
        By registering, You agree to the<br>
        <a href="privacypolicy.html">Terms, Conditions and Policies</a><br>
        of DenGuard & Privacy Policy
      </p>
    </div>
  </div>

  <script>
    function redirectToLogin() 
        {
          window.location.href = "login.php"; 
        }

    $(document).ready(function () {
      $('#userType').change(function () {
        const userType = $(this).val();
        const dynamicFields = $('#dynamicFields');

        dynamicFields.empty();

        if (userType === 'PHI') 
        {
          dynamicFields.append('<input type="text" class="input-field" placeholder="License No" name="license_no">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Specialization" name="specialization">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Working period in Years" name="Workingperiod">');
        }
         else if (userType === 'GN') 
         {
          dynamicFields.append('<input type="text" class="input-field" placeholder="GN Code" name="gn_code">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Working period in Years" name="Workingperiod">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Emergency Contact" name="em_contact">');
        } 
        else if (userType === 'MOH') 
        {
          dynamicFields.append('<input type="text" class="input-field" placeholder="MOH Registration ID" name="moh_reg_id">');         
          dynamicFields.append('<input type="text" class="input-field" placeholder="Specialization" name="specialization">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Emergency Contact" name="em_contact">');

        } 
        else if (userType === 'CP') 
        {
          dynamicFields.append('<input type="text" class="input-field" placeholder="Batch Number" name="batch_number">');
          dynamicFields.append('<input type="text" class="input-field" placeholder="Station Name" name="station_name">');
        }
      });
    });


    $('form').submit(function (e) 
    {
      var phone = $('input[name="phone"]').val();

      if (!/^\d{10}$/.test(phone)) 
      {
        alert("Please enter a valid 10-digit phone number.");
        e.preventDefault(); 
      }
    });
  </script>
</body>
</html>
