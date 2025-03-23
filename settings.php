<?php
session_start();

if (!isset($_SESSION['User_ID'])) 
{
    echo "Please login to view Account";
    header("Location: login.php");
    exit(); 
}

$userRole = $_SESSION['role']; 
$UserID = $_SESSION['User_ID'];
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
                <button onclick="showSection('account')">Account</button>
                <button onclick="showSection('security')">Security</button>
                <button onclick="showSection('contact')">Contact</button>
                <button onclick="showSection('actions')">Actions</button>
                
                <?php if ($userRole === "PHI"): ?>
                    <button id="manageDivisionBtn">Manage Divisions & Districts</button>
                <?php endif; ?>
            </div>

            <div id="account" class="settings-section">
                <h2>Change Username</h2>
                <form method="post" action="updateusername.php">
                    <input type="text" name="username" placeholder="New Username" required>
                    <button type="submit" name="change-username" value="change-username">Update</button>
                </form>
            </div>

            <div id="security" class="settings-section">
                <h2>Change Password</h2>
                <form method="post" action="updatepassword">
                    <input type="password" name="currentPassword" placeholder="Current Password" required>
                    <input type="password" name="newPassword" placeholder="New Password" required>
                    <button type="submit" name="change-password" value="change-password">Update</button>
                </form>
            </div>

            <div id="contact" class="settings-section">
                <h2>Change Contact</h2>
                <form method="post" action="updatecontact.php">
                    <input type="text" name="contact" placeholder="New Contact" required>
                    <button type="submit" name="change-contact" value="change-contact">Update</button>
                </form>
            </div>

            <div id="actions" class="settings-section">
                <h2>Actions</h2>
                <form method="post" action="logout.php">
                    <button type="submit" name="logout" value="logout" class="logout-btn">Logout</button>
                </form>
                <form method="post" action="delete_account.php" onsubmit="return confirm('Are you sure you want to delete your account?');">
                    <button type="submit" name="delete-account" value="delete-account" class="delete-btn">Delete Account</button>
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

        document.getElementById("manageDivisionBtn")?.addEventListener("click", () => 
        {
            window.location.href = "manage_div_dis.php";
        });
       
       function logout() 
       {
           window.location.href ="logout.php";
       }
    </script>

</body>
</html>
