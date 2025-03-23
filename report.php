<?php
include 'dbcon.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="reportstyle.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" type="image/png" href="Images/denguardlogo.png">
  <title>DenGuard Reporting</title>
</head>
<body>
  <div class="form-container">
    <h1>Report a Dengue Case</h1>
    <form action="submit_case.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="imagefile" class="input-field" required>
      <textarea id="description" name="description" rows="4" placeholder="Enter description" required></textarea>
      <input type="text" name="location" placeholder="Location" required>

      <select id="division" name="cmbdivision" class="input-field" required>
        <option value="" disabled selected>Select Division</option>
        <?php
        $query = "SELECT Division_ID, Division_Name FROM division";
        $result = mysqli_query($conn, $query);
        if (!$result) 
        {
            die("Query failed: " . mysqli_error($conn));
        } 
        else 
        {
            while ($row = mysqli_fetch_array($result)) 
            {
                echo "<option value='{$row[0]}'>{$row[1]}</option>";
            }
        }
        ?>
      </select>

      <button type="submit" name="submit-button" class="submit-button">SUBMIT</button>
    </form>
  </div>
</body>
</html>
