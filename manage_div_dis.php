<?php
	include 'dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="sesttingstyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
</head>
<body>

    <div class="navbar">
        <a href="javascript:history.back()">Back</a>
        <a href="home.php">Home</a>
        <a href="account.php">Account</a>
    </div>

    <div class="settings-container">
    <video autoplay muted loop id="background-video">
        <source src="Images/mosvdo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="content">
        <div class="settings-menu">
            <button onclick="showSection('district')">District</button>
            <button onclick="showSection('division')">Division</button>
        </div>

        <div id="division" class="settings-section">
            <h2>Add Division</h2>
            <form method="post" action="disdiv.php">
                <input type="text" name="division" placeholder="Division Name" required>
                <select id="district" name="cmbdistrict" class="cmb" required>
                    <option value="" disabled selected>Select District</option>
                    <?php
                    $query = "SELECT District_ID, District_Name FROM district";
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
                <button type="submit" name="addDivision">Add Division</button>
            </form>
        </div>

        <div id="district" class="settings-section">
            <h2>Add District</h2>
            <form method="post" action="disdiv.php">
                <input type="text" name="districtname" placeholder="District Name" required>
                <button type="submit" name="addDistrict">Add District</button>
            </form>
        </div>
    </div>
</div>


    <script>
        function showSection(sectionId) 
        {
            document.querySelectorAll('.settings-section').forEach(section => 
            {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>

</body>
</html>

