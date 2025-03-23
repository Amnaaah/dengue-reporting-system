<?php
include 'dbcon.php';
session_start();
$div = $_SESSION['divisionId']; 

if (isset($_POST['selectedCaseID'])) 
{
    $selectedCase = $_POST['selectedCaseID'];

    $query = "SELECT User_ID FROM cases WHERE Case_ID = '$selectedCase'";
    $result = mysqli_query($conn, $query);

    if (!$result) 
    {
        echo "Query Error: " . mysqli_error($conn); // Display error if query fails
        exit();
    }
    else 
    {
        $row = mysqli_fetch_array($result);
        $userID = $row['User_ID'];

        $userQuery = "SELECT * FROM general_user WHERE User_ID = '$userID'";
        $userResult = mysqli_query($conn, $userQuery);

        if (!$userResult) 
        {
            echo "User Query Error: " . mysqli_error($conn); // Display error if user query fails
            exit();
        }
        else 
        {
            $userDetails = mysqli_fetch_assoc($userResult);
            // Store user details in session or display directly
            $_SESSION['userDetails'] = $userDetails;



?>
<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <link rel="stylesheet" type="text/css" href="user_detail.css">
</head>
<body>
<div class="sidebar">
    <h2>Navigation</h2>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="cases.php">View All Cases</a></li>
        <li><a href="javascript:history.back()">Go Back</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="user-card">
        <h2>User Details</h2>
        <p><strong>Name:</strong> <?php echo $userDetails['Username']; ?></p>
        <p><strong>Phone:</strong> <?php echo $userDetails['Contact']; ?></p>
        <p><strong>Address:</strong> <?php echo $userDetails['Address']; ?></p>
            </div>
</div>
            </body>
            </html>
            <?php


            exit();
        }
    }
}
else
{
    echo"No user details available!";
    header("Location:javascript:history.back()");
}

?>




